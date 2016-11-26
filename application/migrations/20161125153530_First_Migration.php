<?php
class Migration_First_Migration extends CI_Migration{
    public function up(){
        $this->load->database();
        $db = $this->db;
        $db->query("USE klico");
        $db->query("SET FOREIGN_KEY_CHECKS=0");
        $db->query("DROP TABLE IF EXISTS category");
        $db->query("DROP TABLE IF EXISTS course");
        $db->query("DROP TABLE IF EXISTS course_ingredient");
        $db->query("DROP TABLE IF EXISTS course_menu");
        $db->query("DROP TABLE IF EXISTS course_sale");
        $db->query("DROP TABLE IF EXISTS ingredient");
        $db->query("DROP TABLE IF EXISTS menu");
        $db->query("DROP TABLE IF EXISTS r_table");
        $db->query("DROP TABLE IF EXISTS restaurant");
        $db->query("DROP TABLE IF EXISTS s_order");
        $db->query("DROP TABLE IF EXISTS sale");
        $db->query("DROP TABLE IF EXISTS service");
        $db->query("DROP TABLE IF EXISTS session");
        $db->query("DROP TABLE IF EXISTS user");
        $db->query("DROP TABLE IF EXISTS user_restaurant");
        $db->query("SET FOREIGN_KEY_CHECKS=1");
        echo "<p>Terminado!</p>";
        $db->query("CREATE TABLE user (
                id_user INT UNSIGNED AUTO_INCREMENT COMMENT 'Identificador de usuario',
                login VARCHAR(40) COMMENT 'Nombre de usuario',
                pass VARCHAR(128) COMMENT 'Hash de la contraseña',
                email VARCHAR(40) COMMENT 'Dirección de correo',
                CONSTRAINT pk_user PRIMARY KEY (id_user)
                )");
        $db->insert("user",["login"=>"owner","pass"=>password_hash("owner",PASSWORD_DEFAULT),"email"=>"owner@restaurant.com"]);
        $db->insert("user",["login"=>"user","pass"=>password_hash("user",PASSWORD_DEFAULT),"email"=>"user@user.com"]);
        $db->query("CREATE TABLE session (
                        id_session VARCHAR(32) COMMENT 'Identificador de la sesión de php',
                        id_user INT UNSIGNED COMMENT 'Identificador de usuario',
                        dateStart TIMESTAMP COMMENT 'Timestamp de inicio de la sesión',
                        lastActivity TIMESTAMP COMMENT 'Tiemsamp de la última actividad',
                        ip VARCHAR(50) COMMENT 'Dirección ip del usuario',
                        userAgent VARCHAR(50) COMMENT 'Explorador utilizado',
                        os VARCHAR(40) COMMENT 'Systema utilizado',
                        CONSTRAINT pk_session PRIMARY KEY (id_session),
                        CONSTRAINT fk_session_user FOREIGN KEY (id_user)
                        REFERENCES user(id_user)
                    )");
        $db->query("CREATE TABLE restaurant (
            id_restaurant INT UNSIGNED AUTO_INCREMENT COMMENT 'Identificador del restaurante',
            name VARCHAR(50) COMMENT 'Nombre del restaurante',
            location VARCHAR(250) COMMENT 'Localización del restaurante',
            logo VARCHAR(100) COMMENT 'Logo del restaurante',
            slug VARCHAR(50) COMMENT 'Url del restaurante',
            cif VARCHAR(12) COMMENT 'Identificador fiscal',
            email VARCHAR(40) COMMENT 'Dirección de correo',
            phone1 VARCHAR(12) COMMENT 'Teléfono',
            phone2 VARCHAR(12) COMMENT 'Segundo Teléfono',
            CONSTRAINT pk_restaurant PRIMARY KEY (id_restaurant)
        )");
        $db->query("CREATE TABLE user_restaurant (
            id_user INT UNSIGNED COMMENT 'Identificador de usuario',
            id_restaurant INT UNSIGNED COMMENT 'Identificador de restaurante',
            CONSTRAINT fk_user_restaurant_restaurant FOREIGN KEY (id_restaurant ) REFERENCES restaurant(id_restaurant),
            CONSTRAINT fk_user_restaurant_user FOREIGN KEY (id_user ) REFERENCES user(id_user),
            CONSTRAINT pk_user_restaurant PRIMARY KEY (id_user,id_restaurant)
        )");
        $db->query("CREATE TABLE category (
                id_category INT UNSIGNED AUTO_INCREMENT COMMENT 'Identificador de la categoía',
                id_restaurant INT UNSIGNED NOT NULL COMMENT 'Identificador del restaurante',
                name VARCHAR(40) NOT NULL COMMENT 'Nombre de la categoría',
                CONSTRAINT pk_category PRIMARY KEY (id_category),
                CONSTRAINT fk_category_restaurant FOREIGN KEY 
                (id_restaurant) REFERENCES restaurant(id_restaurant)
        )");
        $db->query("CREATE TABLE course (
                      id_course INT UNSIGNED AUTO_INCREMENT COMMENT 'Identificador de platos',
                      id_restaurant INT UNSIGNED NOT NULL,
                      name VARCHAR(40),
                      description TEXT,
                      image VARCHAR(40),
                      price DECIMAL(8,2),
                      CONSTRAINT pk_course PRIMARY KEY (id_course),
                      CONSTRAINT fk_course_restaurant FOREIGN KEY (id_restaurant)
                      REFERENCES restaurant(id_restaurant)
                    )");
        $db->query("CREATE TABLE ingredient(
                      id_ingredient INT UNSIGNED AUTO_INCREMENT,
                      id_restaurant INT UNSIGNED NOT NULL,
                      name VARCHAR(45),
                      stock BIT,
                      CONSTRAINT pk_ingredient PRIMARY KEY(id_ingredient),
                      CONSTRAINT fk_ingredient_restaurant FOREIGN KEY (id_restaurant)
                      REFERENCES restaurant(id_restaurant)
                    )");
        $db->query("CREATE TABLE course_ingredient (
                      id_course INT UNSIGNED NOT NULL,
                      id_ingredient INT UNSIGNED NOT NULL,
                      CONSTRAINT pk_course_ingredient PRIMARY KEY(id_course,id_ingredient),
                      CONSTRAINT fk_course_ingredient_course FOREIGN KEY (id_course)
                      REFERENCES course(id_course),
                      CONSTRAINT fk_course_ingredient_ingredient FOREIGN KEY (id_ingredient)
                      REFERENCES ingredient(id_ingredient)
                    )");
        $db->query("CREATE TABLE menu(
                    id_menu INT UNSIGNED AUTO_INCREMENT,
                    id_restaurant INT UNSIGNED NOT NULL,
                    name VARCHAR(45) NOT NULL,
                    price DECIMAL(6,2) NOT NULL,
                    CONSTRAINT pk_menu PRIMARY KEY (id_menu),
                    CONSTRAINT fk_menu_restaurant FOREIGN KEY
                    (id_restaurant) REFERENCES restaurant(id_restaurant)
                  )");
        $db->query("CREATE TABLE course_menu(
                    id_menu INT UNSIGNED NOT NULL,
                    id_course INT UNSIGNED NOT NULL,
                    clasification VARCHAR(45) NOT NULL,
                    c_order TINYINT UNSIGNED NOT NULL,
                    CONSTRAINT pk_course_menu PRIMARY KEY (id_menu,id_course),
                    CONSTRAINT fk_course_menu_menu FOREIGN KEY (id_menu) REFERENCES menu(id_menu),
                    CONSTRAINT fk_course_menu_course FOREIGN  KEY (id_course) REFERENCES  course(id_course)
                  )");
        $db->query("CREATE TABLE sale(
                      id_sale INT UNSIGNED AUTO_INCREMENT,
                      id_restaurant INT,
                      name VARCHAR(45) NOT NULL,
                      active BIT,
                      description VARCHAR(200),
                      CONSTRAINT pk_sale PRIMARY KEY (id_sale)
                    )");
        $db->query("CREATE TABLE course_sale(
                    id_course INT UNSIGNED NOT NULL,
                    id_sale INT UNSIGNED NOT NULL,
                    price DECIMAL(6,2),
                    CONSTRAINT pk_course_sale PRIMARY KEY (id_course,id_sale),
                    CONSTRAINT fk_course_sale_course FOREIGN KEY
                    (id_course) REFERENCES course(id_course),
                    CONSTRAINT fk_course_sale_sale FOREIGN KEY
                    (id_sale) REFERENCES sale(id_sale)
                  )");
        $db->query("CREATE TABLE r_table(
                    id_table SMALLINT UNSIGNED,
                    id_retaurant INT UNSIGNED,
                    CONSTRAINT pk_table PRIMARY KEY (id_table,id_retaurant),
                    CONSTRAINT fk_table_restaurant FOREIGN KEY (id_retaurant)
                    REFERENCES restaurant(id_restaurant)
                  )");
        $db->query("CREATE TABLE service (
                      id_service INT UNSIGNED AUTO_INCREMENT,
                      id_restaurant INT UNSIGNED NOT NULL,
                      id_session VARCHAR(32) NOT NULL,
                      id_table SMALLINT UNSIGNED NOT NULL,
                      CONSTRAINT pk_service PRIMARY KEY (id_service),
                      CONSTRAINT fk_service_restaurant FOREIGN KEY (id_restaurant)
                      REFERENCES restaurant(id_restaurant),
                      CONSTRAINT fk_service_session FOREIGN KEY (id_session)
                      REFERENCES session(id_session),
                      CONSTRAINT fk_service_table FOREIGN KEY (id_table) REFERENCES 
                      r_table(id_table)
                    )");
        $db->query("CREATE TABLE s_order (
                      id_order INT UNSIGNED AUTO_INCREMENT,
                      id_service INT UNSIGNED NOT NULL,
                      id_course INT UNSIGNED NOT NULL,
                      id_menu INT UNSIGNED,
                      id_sale INT UNSIGNED,
                      ammount TINYINT UNSIGNED,
                      CONSTRAINT pk_order PRIMARY KEY (id_order),
                      CONSTRAINT fk_order_service FOREIGN KEY (id_service)
                      REFERENCES service(id_service),
                      CONSTRAINT fk_order_course FOREIGN KEY (id_course)
                      REFERENCES course(id_course),
                      CONSTRAINT fk_order_menu FOREIGN KEY (id_menu)
                      REFERENCES menu(id_menu),
                      CONSTRAINT fk_order_sale FOREIGN KEY (id_sale)
                      REFERENCES sale(id_sale)
                  )");

    }
    public function down(){
        $this->load->database();
        $db = $this->db;
        $db->query("USE klico");
        $db->query("SET FOREIGN_KEY_CHECKS=0");
        $db->query("DROP TABLE IF EXISTS category");
        $db->query("DROP TABLE IF EXISTS course");
        $db->query("DROP TABLE IF EXISTS course_ingredient");
        $db->query("DROP TABLE IF EXISTS course_menu");
        $db->query("DROP TABLE IF EXISTS course_sale");
        $db->query("DROP TABLE IF EXISTS ingredient");
        $db->query("DROP TABLE IF EXISTS menu");
        $db->query("DROP TABLE IF EXISTS r_table");
        $db->query("DROP TABLE IF EXISTS restaurant");
        $db->query("DROP TABLE IF EXISTS s_order");
        $db->query("DROP TABLE IF EXISTS sale");
        $db->query("DROP TABLE IF EXISTS service");
        $db->query("DROP TABLE IF EXISTS session");
        $db->query("DROP TABLE IF EXISTS user");
        $db->query("DROP TABLE IF EXISTS user_restaurant");
        $db->query("SET FOREIGN_KEY_CHECKS=1");
    }
}