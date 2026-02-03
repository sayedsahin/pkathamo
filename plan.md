# For Async
RateLimit Driver and middleware

CREATE TABLE remember_tokens (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    user_agent VARCHAR(255),
    created_at DATETIME
);