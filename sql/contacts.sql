/*
    Name: Jerome Acosta
    Course Code: SYST10199
    Date: April 6, 2022
*/

-- SQL statements to reset table.

DROP TABLE IF EXISTS contacts;


CREATE TABLE contacts(
    email_address VARCHAR(255) PRIMARY KEY,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    phone_number VARCHAR(25) NOT NULL,
    created_on DATE DEFAULT now()
);

INSERT INTO contacts(email_address, first_name, last_name, phone_number) VALUES("Muath.alzghool@mail.com", "Muath", "Alzghool", "(123)435-4890");
INSERT INTO contacts(email_address, first_name, last_name, phone_number) VALUES("acosjero@sheridancollege.ca", "Jerome", "Acosta", "(647)528-8952");

SELECT * FROM contacts;