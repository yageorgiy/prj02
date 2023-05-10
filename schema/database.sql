CREATE TABLE users (
                               id BIGINT UNSIGNED auto_increment NOT NULL,
                               username MEDIUMTEXT NULL,
                               session_key MEDIUMTEXT NULL,
                               score INT NULL,
                               current_page mediumtext,
                               CONSTRAINT users_PK PRIMARY KEY (id)
)
    ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;
CREATE UNIQUE INDEX users_id_IDX USING BTREE ON users (id);

CREATE TABLE game (
                              id BIGINT UNSIGNED auto_increment NOT NULL,
                              page_start MEDIUMTEXT NULL,
                              page_end MEDIUMTEXT NULL,
                              game_start datetime DEFAULT CURRENT_TIMESTAMP,
                              server_count_transitions INTEGER NULL,
                              current_player_id bigint unsigned DEFAULT NULL,
                              CONSTRAINT game_PK PRIMARY KEY (id)
)
    ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;
CREATE UNIQUE INDEX game_id_IDX USING BTREE ON game (id);
