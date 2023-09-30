<?php

// The following file will be contained in .gitignore

// Enum that represents the posts table in our db
class UsersFields {
    const ID = 'id';
    const TABLE_NAME = 'users';
    const USERNAME = 'username';
    const BIRTH_DATE = 'birth_date';
    const ACTIVE = 'user_active';
    const EMAIL = 'email';
}

// Enum that represents the posts table in our db
class PostsFields {
    const TABLE_NAME = 'posts';
    const ID = 'id';
    const ACTIVE = 'post_active';
    const TITLE = 'title';
    const BODY = 'body';
    const CREATED_AT = 'created_at';
    const USERID = 'userId';
}

class PostsPerHourFields {
    const ID = 'id';
    const TABLE_NAME = 'posts_per_hour';
    const DATE = 'date';
    const HOUR = 'hour';
    const POSTS_PER_HOUR = 'post_count';
}

?>