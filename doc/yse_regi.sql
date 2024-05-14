CREATE TABLE sales (
    id bigint PRIMARY KEY AUTO_INCREMENT,
    sales int NOT NULL,
    sales_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime NULL DEFAULT NULL
);