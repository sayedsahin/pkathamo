<?php declare(strict_types=1);

namespace App\Systems;

use PDO;
use RuntimeException;

class QueryBuilder
{
    protected PDO $pdo;

    protected string $table = '';
    protected array $select = ['*'];
    protected array $joins = [];
    protected array $wheres = [];
    protected array $bindings = [];
    protected string $order = '';
    protected string $limit = '';

    private int $paramCounter = 0;

    // Full raw SQL mode (builder bypass)
    private ?string $rawSql = null;

    public function __construct(?Database $db = null)
    {
        if ($db === null) {
            global $container;
            $db = $container->make(Database::class);
        }
        $this->pdo = $db->pdo;
    }


    /* ============================================================
       RAW SQL (FULL QUERY MODE)
    ============================================================ */

    public function raw(string $sql, array $bindings = []): self
    {
        $this->rawSql = $sql;
        $this->bindings = $bindings;
        return $this;
    }



    /* ============================================================
       CHAINING
    ============================================================ */

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function select(string ...$columns): self
    {
        $this->select = $columns ?: ['*'];
        return $this;
    }


    /* ============================================================
       BASIC WHERE
    ============================================================ */

    public function where(string $column, $operator = null, $value = null, string $boolean = 'AND'): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        $param = $this->param();
        $this->bindings[$param] = $value;

        $this->wheres[] = [
            'boolean' => $boolean,
            'sql'     => "$column $operator $param",
        ];

        return $this;
    }

    public function orWhere(string $column, $operator = null, $value = null): self
    {
        return $this->where($column, $operator, $value, 'OR');
    }


    /* ============================================================
       SAFE whereRaw() AND orWhereRaw()
       Uses PDO-style ? placeholders → 100% SQL Injection Safe
    ============================================================ */

    public function whereRaw(string $sql, array $bindings = [], string $boolean = 'AND'): self
    {
        $index = 0;

        $sql = preg_replace_callback('/\?/', function () use (&$index, $bindings) {
            $param = $this->param();
            $this->bindings[$param] = $bindings[$index++];
            return $param;
        }, $sql);

        $this->wheres[] = [
            'boolean' => $boolean,
            'sql'     => $sql,
        ];

        return $this;
    }

    public function orWhereRaw(string $sql, array $bindings = []): self
    {
        return $this->whereRaw($sql, $bindings, 'OR');
    }



    /* ============================================================
       NULL CHECK
    ============================================================ */

    public function whereNull(string $column, string $boolean = 'AND'): self
    {
        $this->wheres[] = ['boolean' => $boolean, 'sql' => "$column IS NULL"];
        return $this;
    }

    public function whereNotNull(string $column, string $boolean = 'AND'): self
    {
        $this->wheres[] = ['boolean' => $boolean, 'sql' => "$column IS NOT NULL"];
        return $this;
    }


    /* ============================================================
       LIKE
    ============================================================ */

    public function like(string $column, string $value, string $boolean = 'AND'): self
    {
        $param = $this->param();
        $this->bindings[$param] = $value;

        $this->wheres[] = ['boolean' => $boolean, 'sql' => "$column LIKE $param"];
        return $this;
    }


    /* ============================================================
       JOIN
    ============================================================ */

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    public function innerJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'INNER');
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }



    /* ============================================================
       ORDER & LIMIT
    ============================================================ */

    public function order(string $order): self
    {
        $this->order = " ORDER BY $order";
        return $this;
    }

    // no need to use limit in first() because first return only one item
    public function limit(int $limit, ?int $offset = null): self
    {
        $this->limit = " LIMIT $limit";
        if ($offset !== null) {
            $this->limit .= " OFFSET $offset";
        }
        return $this;
    }



    /* ============================================================
       EXECUTION
    ============================================================ */

    public function get(): array
    {
        // Full raw query
        try {
            if ($this->rawSql) {
                $stmt = $this->pdo->prepare($this->rawSql);
                $stmt->execute($this->bindings);
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $data;
            }

            $sql = $this->toSql();
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->bindings);

            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $data;
        } finally {
            $this->reset();
        }
    }


    /* -------- Correct first(): runtime LIMIT 1 -------- */

    public function first(): ?object
    {
        try {
            $sql = ($this->rawSql ?: $this->toSql()) . " LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->bindings);

            $result = $stmt->fetch(PDO::FETCH_OBJ) ?: null;
            return $result;
        } finally {
            $this->reset();
        }
    }


    public function find(int $id, string $column = 'id'): ?object
    {
        return $this->where($column, $id)->first();
    }


    public function exists(): bool
    {
        try {
            $sql = "SELECT EXISTS(SELECT 1 FROM {$this->table}"
                . $this->compileWheres()
                . ")";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->bindings);

            $exists = (bool)$stmt->fetchColumn();

            return $exists;
        } finally {
            $this->reset();
        }
    }

    public function count(string $column = '*'): int
    {
        try {
            if ($this->rawSql) {
                $sql = "SELECT COUNT(*) AS total FROM ({$this->rawSql}) AS sub";
            } else {
                $sql = "SELECT COUNT({$column}) AS total FROM {$this->table}"
                    . $this->compileJoins()
                    . $this->compileWheres();
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->bindings);

            $total = (int) $stmt->fetchColumn();

            return $total;
        } finally {
            $this->reset();
        }
    }



    /* ============================================================
       INSERT / UPDATE / DELETE
    ============================================================ */

    public function insert(array $data, bool $returnId = false): bool|int
    {
        try {
            $cols = array_keys($data);
            $placeholders = [];

            foreach ($data as $col => $val) {
                $p = $this->param();
                $this->bindings[$p] = $val;
                $placeholders[] = $p;
            }

            $sql = "INSERT INTO {$this->table} ("
                . implode(', ', $cols)
                . ") VALUES ("
                . implode(', ', $placeholders)
                . ")";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->bindings);

            $id = $returnId ? (int) $this->pdo->lastInsertId() : true;

            return $id;
        } finally {
            $this->reset();
        }
    }


    public function update(array $data): bool
    {
        try {
            if (empty($this->wheres)) {
                throw new RuntimeException("UPDATE without WHERE is forbidden!");
            }

            $set = [];
            foreach ($data as $col => $val) {
                $p = $this->param();
                $this->bindings[$p] = $val;
                $set[] = "$col = $p";
            }

            $sql = "UPDATE {$this->table} SET "
                . implode(', ', $set)
                . $this->compileWheres();

            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute($this->bindings);

            return $ok;
        } finally {
            $this->reset();
        }
    }

    /**
        @return bool|int  true/false on update, insertId on insert
    */

    public function updateOrInsert(array $search, array $data = []): bool|int
    {
        $checker = clone $this;
        $checker->whereConditions($search);

        if ($checker->exists()) {
            $this->whereConditions($search);
            $this->update($data ?: $search);
            return true;
        }

        $insertData = array_merge($data, $search);
        return $this->insert($insertData, true);
    }


    public function delete(): bool
    {
        if (empty($this->wheres)) {
            throw new RuntimeException("DELETE without WHERE is forbidden!");
        }

        try {
            $sql = "DELETE FROM {$this->table}" . $this->compileWheres();

            $stmt = $this->pdo->prepare($sql);
            $ok = $stmt->execute($this->bindings);

            return $ok;
        } finally {
            $this->reset();
        }
    }



    /* ============================================================
       SQL BUILDERS
    ============================================================ */

    public function toSql(): string
    {
        return "SELECT " . implode(', ', $this->select)
            . " FROM {$this->table}"
            . $this->compileJoins()
            . $this->compileWheres()
            . $this->order
            . $this->limit;
    }

    private function compileJoins(): string
    {
        return $this->joins ? " " . implode(" ", $this->joins) : '';
    }

    private function compileWheres(): string
    {
        if (empty($this->wheres)) return '';

        $parts = [];
        foreach ($this->wheres as $i => $w) {
            $bool = $i === 0 ? 'WHERE' : $w['boolean'];
            $parts[] = "$bool {$w['sql']}";
        }

        return ' ' . implode(' ', $parts);
    }


    /* ============================================================
       HELPERS
    ============================================================ */

    private function param(): string
    {
        return ':p' . (++$this->paramCounter);
    }

    public function whereConditions(array $conditions, string $boolean = 'AND'): self
    {
        foreach ($conditions as $column => $value) {
            $this->where($column, '=', $value, $boolean);
        }
        return $this;
    }

    public function pluck(string $column): array
    {
        try {
            $this->select($column);

            $sql = $this->rawSql ?: $this->toSql();

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute($this->bindings);

            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } finally {
            $this->reset();
        }
    }

    public function value(string $column): mixed
    {
        try {
            $this->select($column);

            $sql = ($this->rawSql ?: $this->toSql()) . ' LIMIT 1';

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute($this->bindings);

            $value = $stmt->fetchColumn();

            return $value === false ? null : $value;
        } finally {
            $this->reset();
        }
    }

    private function reset(): void
    {
        // $this->table = ''; // keep table for model query. table fixed in model class
        $this->order =
        $this->limit =
        $this->rawSql = '';

        $this->select = ['*'];
        $this->joins = [];
        $this->wheres = [];
        $this->bindings = [];
        $this->paramCounter = 0;
    }
}
