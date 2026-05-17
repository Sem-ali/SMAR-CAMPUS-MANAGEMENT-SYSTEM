  -- 1. Supertype Table
CREATE TABLE Person (
    person_id NUMBER PRIMARY KEY,
    first_name VARCHAR2(50) NOT NULL,
    last_name VARCHAR2(50) NOT NULL,
    email VARCHAR2(100) UNIQUE NOT NULL,
    phone VARCHAR2(20),
    registration_date DATE DEFAULT SYSDATE,
    person_type VARCHAR2(1) CHECK (person_type IN ('S', 'E'))
);

-- 2. Student Subtype
CREATE TABLE Student (
    person_id NUMBER PRIMARY KEY,
    student_no VARCHAR2(20) UNIQUE NOT NULL,
    enroll_year NUMBER(4),
    major VARCHAR2(50),
    CONSTRAINT fk_stud_person FOREIGN KEY (person_id) REFERENCES Person(person_id)
);

-- 3. Employee Subtype
CREATE TABLE Employee (
    person_id NUMBER PRIMARY KEY,
    hire_date DATE NOT NULL,
    department VARCHAR2(50),
    role VARCHAR2(20) CHECK (role IN ('Professor', 'Administrator', 'Director')),
    CONSTRAINT fk_emp_person FOREIGN KEY (person_id) REFERENCES Person(person_id)
);

-- 4. Services Catalog
CREATE TABLE Services (
    service_id NUMBER PRIMARY KEY,
    name VARCHAR2(100) NOT NULL,
    category VARCHAR2(20) CHECK (category IN ('IT', 'Administrative', 'Academic')),
    description VARCHAR2(500)
);

-- 5. Requests Table
CREATE TABLE Requests (
    request_id NUMBER PRIMARY KEY,
    title VARCHAR2(100) NOT NULL,
    status VARCHAR2(20) CHECK (status IN ('Pending', 'In Progress', 'Completed')) NOT NULL,
    creation_date DATE DEFAULT SYSDATE,
    student_id NUMBER NOT NULL,
    service_id NUMBER NOT NULL,
    CONSTRAINT fk_req_student FOREIGN KEY (student_id) REFERENCES Student(person_id),
    CONSTRAINT fk_req_service FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

-- 6. Appointments Table
CREATE TABLE Appointments (
    appt_id NUMBER PRIMARY KEY,
    subject VARCHAR2(100),
    start_time TIMESTAMP NOT NULL,
    status VARCHAR2(20) CHECK (status IN ('Scheduled', 'Completed', 'Cancelled')) NOT NULL,
    student_id NUMBER NOT NULL,
    employee_id NUMBER NOT NULL,
    service_id NUMBER NOT NULL,
    CONSTRAINT fk_appt_student FOREIGN KEY (student_id) REFERENCES Student(person_id),
    CONSTRAINT fk_appt_employee FOREIGN KEY (employee_id) REFERENCES Employee(person_id),
    CONSTRAINT fk_appt_service FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

-- 7. Messages Table
CREATE TABLE Messages (
    message_id NUMBER PRIMARY KEY,
    subject VARCHAR2(100),
    body CLOB,
    sender_id NUMBER NOT NULL,
    receiver_id NUMBER NOT NULL,
    CONSTRAINT fk_sender FOREIGN KEY (sender_id) REFERENCES Person(person_id),
    CONSTRAINT fk_receiver FOREIGN KEY (receiver_id) REFERENCES Person(person_id)
);

-- 8. Feedback Table (Arc Implementation)
CREATE TABLE Feedback (
    feedback_id NUMBER PRIMARY KEY,
    rating NUMBER CHECK (rating BETWEEN 1 AND 5),
    comments VARCHAR2(500),
    request_id NUMBER,
    appointment_id NUMBER,
    CONSTRAINT fk_fb_req FOREIGN KEY (request_id) REFERENCES Requests(request_id),
    CONSTRAINT fk_fb_appt FOREIGN KEY (appointment_id) REFERENCES Appointments(appt_id),
    CONSTRAINT arc_check CHECK (
        (request_id IS NOT NULL AND appointment_id IS NULL) OR 
        (request_id IS NULL AND appointment_id IS NOT NULL)
    )
);
-- Insert Persons
INSERT INTO Person VALUES (1, 'Hachim', 'Abakar', 'hachim@email.com', '0555111', SYSDATE, 'S');
INSERT INTO Person VALUES (2, 'Saleh', 'Bakri', 'saleh@email.com', '0555222', SYSDATE, 'S');
INSERT INTO Person VALUES (3, 'Ahmet', 'Yilmaz', 'ahmet@email.com', '0555333', SYSDATE, 'E');

-- Insert Subtypes
INSERT INTO Student VALUES (1, '2022001', 2022, 'Computer Science');
INSERT INTO Student VALUES (2, '2022002', 2022, 'Information Technology');
INSERT INTO Employee VALUES (3, TO_DATE('2020-01-01', 'YYYY-MM-DD'), 'IT Dept', 'Professor');

-- Insert Services
INSERT INTO Services VALUES (10, 'Transcript', 'Administrative', 'Academic records');
INSERT INTO Services VALUES (20, 'IT Support', 'IT', 'Technical help');

-- Insert Requests
INSERT INTO Requests VALUES (101, 'Need Transcript', 'Administrative request for transcript','Pending', SYSDATE,NULL, 1, 10);
INSERT INTO Requests VALUES (102, 'WiFi Issue', 'Description of issue here', 'Completed', SYSDATE, NULL, 2, 20);
-- Insert Appointments
INSERT INTO Appointments VALUES (201, 'Project Review', TO_TIMESTAMP('2026-05-20 10:00', 'YYYY-MM-DD HH24:MI'), 'Scheduled', 1, 3, 20);

-- Insert Messages
INSERT INTO Messages (message_id, subject, body, sender_id, receiver_id)
VALUES (501, 'Question', 'Is the lab open?', 1, 2);

-- Insert Feedback (The Arc Test)
INSERT INTO Feedback VALUES (901, 5, 'Great help', 102, NULL);
INSERT INTO Feedback VALUES (902, 4, 'Good meeting', NULL, 201);
-- 1. JOIN: Show Student Name and their Request Title
SELECT p.first_name, p.last_name, r.title 
FROM Person p 
JOIN Requests r ON p.person_id = r.student_id;

-- 2. SUBQUERY: Find Services that have no requests
SELECT name FROM Services 
WHERE service_id NOT IN (SELECT service_id FROM Requests);

-- 3. GROUP BY: Count requests by Status
SELECT status, COUNT(*) AS Total FROM Requests GROUP BY status;

-- 4. CHARACTER FUNCTION: Format Names for a Directory
SELECT UPPER(last_name) || ', ' || first_name AS Student_List FROM Person WHERE person_type = 'S';

-- 5. DATE FUNCTION: Show age of accounts (in days)
SELECT first_name, ROUND(SYSDATE - registration_date) AS Days_Registered FROM Person;

-----------------------------------------------------
--the missing part or elemennt of from the entities
-----------------------------------------------------
/*
-- Table: person
CREATE TABLE person (
    person_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    created_date DATE
) ENGINE=InnoDB;

-- Table: student (subtype)
CREATE TABLE student (
    person_id INT PRIMARY KEY,
    student_number VARCHAR(20) NOT NULL UNIQUE,
    major VARCHAR(50) NOT NULL,
    enrollment_year INT,
    FOREIGN KEY (person_id) REFERENCES person(person_id)
) ENGINE=InnoDB;

-- Table: employee (subtype)
CREATE TABLE employee (
    person_id INT PRIMARY KEY,
    hire_date DATE,
    employee_type VARCHAR(20) NOT NULL CHECK (employee_type IN ('FACULTY','ADMIN','DIRECTOR')),
    FOREIGN KEY (person_id) REFERENCES person(person_id)
) ENGINE=InnoDB;

-- Table: faculty
CREATE TABLE faculty (
    person_id INT PRIMARY KEY,
    academic_rank VARCHAR(30),
    department VARCHAR(50) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES employee(person_id)
) ENGINE=InnoDB;

-- Table: administrator
CREATE TABLE administrator (
    person_id INT PRIMARY KEY,
    admin_role VARCHAR(30) NOT NULL,
    department VARCHAR(50) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES employee(person_id)
) ENGINE=InnoDB;

-- Table: director
CREATE TABLE director (
    person_id INT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    area_of_responsibility VARCHAR(100) NOT NULL,
    appointment_start_date DATE NOT NULL,
    FOREIGN KEY (person_id) REFERENCES employee(person_id)
) ENGINE=InnoDB;

-- Table: service_category
CREATE TABLE service_category (
    cat_id INT AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(200)
) ENGINE=InnoDB;

-- Table: service
CREATE TABLE service (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    description VARCHAR(200),
    cat_id INT NOT NULL,
    FOREIGN KEY (cat_id) REFERENCES service_category(cat_id)
) ENGINE=InnoDB;

-- Table: request
CREATE TABLE request (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(500) NOT NULL,
    status VARCHAR(20) NOT NULL,
    created_date DATE NOT NULL,
    resolution_date DATE NOT NULL,
    priority VARCHAR(10),
    requester_person_id INT NOT NULL,
    assignee_person_id INT,
    service_id INT NOT NULL,
    FOREIGN KEY (requester_person_id) REFERENCES person(person_id),
    FOREIGN KEY (assignee_person_id) REFERENCES person(person_id),
    FOREIGN KEY (service_id) REFERENCES service(service_id)
) ENGINE=InnoDB;

-- Table: appointment
CREATE TABLE appointment (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(100),
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    location VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL,
    organizer_person_id INT NOT NULL,
    attendee_person_id INT NOT NULL,
    request_id INT NOT NULL,
    FOREIGN KEY (organizer_person_id) REFERENCES person(person_id),
    FOREIGN KEY (attendee_person_id) REFERENCES person(person_id),
    FOREIGN KEY (request_id) REFERENCES request(request_id)
) ENGINE=InnoDB;

-- Table: message
CREATE TABLE message (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(200),
    body TEXT NOT NULL,
    sent_datetime DATETIME NOT NULL,
    read_status VARCHAR(10) NOT NULL,
    sender_person_id INT NOT NULL,
    receiver_person_id INT NOT NULL,
    request_id INT NOT NULL,
    FOREIGN KEY (sender_person_id) REFERENCES person(person_id),
    FOREIGN KEY (receiver_person_id) REFERENCES person(person_id),
    FOREIGN KEY (request_id) REFERENCES request(request_id)
) ENGINE=InnoDB;

-- Table: feedback
CREATE TABLE feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments VARCHAR(500) NOT NULL,
    submitted_date DATE NOT NULL,
    request_id INT NOT NULL,
    author_person_id INT NOT NULL,
    FOREIGN KEY (request_id) REFERENCES request(request_id),
    FOREIGN KEY (author_person_id) REFERENCES person(person_id)
) ENGINE=InnoDB;
*/
