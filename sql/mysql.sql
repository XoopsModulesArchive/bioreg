#
# Table structure for table `bioreg_genus`
#

CREATE TABLE bioreg_genus (
    genus_key   INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    genus_name  VARCHAR(50)     NOT NULL DEFAULT '',
    family_name VARCHAR(50)              DEFAULT NULL,
    create_time DATETIME                 DEFAULT NULL,
    created_by  VARCHAR(50)              DEFAULT NULL,
    last_update TIMESTAMP(14)   NOT NULL,
    updated_by  VARCHAR(50)              DEFAULT NULL,
    PRIMARY KEY (genus_key),
    UNIQUE KEY bioreg_gen_uk1 (genus_name),
    KEY bioreg_gen_fam (family_name, genus_name)
)
    ENGINE = ISAM;

#
# Table structure for table `bioreg_species`
#

CREATE TABLE bioreg_species (
    species_key  INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    species_name VARCHAR(50)              DEFAULT NULL,
    genus_key    INT(8) UNSIGNED NOT NULL DEFAULT '0',
    group_name   VARCHAR(50)              DEFAULT NULL,
    create_time  DATETIME                 DEFAULT NULL,
    created_by   VARCHAR(50)              DEFAULT NULL,
    last_update  TIMESTAMP(14)   NOT NULL,
    updated_by   VARCHAR(50)              DEFAULT NULL,
    PRIMARY KEY (species_key),
    UNIQUE KEY bioreg_spec_uk1 (genus_key, species_name)
)
    ENGINE = ISAM;

#
# Table structure for table `bioreg_population`
#

CREATE TABLE bioreg_population (
    pop_key     INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    species_key INT(8) UNSIGNED NOT NULL DEFAULT '0',
    pop_name    VARCHAR(50)              DEFAULT '',
    common_name VARCHAR(50)              DEFAULT NULL,
    pop_url     VARCHAR(50)              DEFAULT NULL,
    status      CHAR(2)                  DEFAULT 'A',
    create_time DATETIME                 DEFAULT NULL,
    created_by  VARCHAR(50)              DEFAULT NULL,
    last_update TIMESTAMP(14)   NOT NULL,
    updated_by  VARCHAR(50)              DEFAULT NULL,
    PRIMARY KEY (pop_key),
    UNIQUE KEY bioreg_loc_uk1 (species_key, pop_name),
    KEY bioreg_loc_name (pop_name),
    KEY bioreg_loc_cname (common_name)
)
    ENGINE = ISAM;

#
# Table structure for table `bioreg_listings`
#

CREATE TABLE bioreg_listings (
    listings_key     INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    pop_key          INT(8) UNSIGNED NOT NULL DEFAULT '0',
    listings_uid     MEDIUMINT(8) UNSIGNED    DEFAULT NULL,
    listings_userid  VARCHAR(60)              DEFAULT NULL,
    listings_price   VARCHAR(20)              DEFAULT NULL,
    listings_type    CHAR(2)                  DEFAULT 'R',
    listings_status  CHAR(2)                  DEFAULT 'N',
    listings_visible CHAR(2)                  DEFAULT 'S',
    listings_comment TEXT,
    create_time      DATETIME                 DEFAULT NULL,
    last_update      TIMESTAMP(14)   NOT NULL,
    PRIMARY KEY (listings_key),
    KEY bioreg_list_user (listings_userid)
)
    ENGINE = ISAM;

