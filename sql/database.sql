CREATE TABLE Professor (
    SSN CHAR(9) PRIMARY KEY,                     -- Social Security Number as the primary key
    ProfName VARCHAR(50) NOT NULL,               -- Professor's name
    Sex CHAR(1),                                 -- Gender (M/F/Other)
    Title VARCHAR(50),                           -- Title (e.g., Dr., Prof.)
    Salary DECIMAL(10, 2),                       -- Salary
    StreetAddress VARCHAR(100),                  -- Street address
    City VARCHAR(50),
    State CHAR(2),
    ZIP CHAR(5),
    AreaCode CHAR(3),
    Digits CHAR(7)                               -- Phone number digits
);

CREATE TABLE Department (
    DeptNumber INT PRIMARY KEY,                  -- Unique identifier for department
    DeptName VARCHAR(50) NOT NULL,               -- Department name
    DeptPhone CHAR(10),                          -- Phone number for department
    OfficeLocation VARCHAR(50),                  -- Office location
    ChairSSN CHAR(9),                            -- SSN of the professor who is chair
    FOREIGN KEY (ChairSSN) REFERENCES Professor(SSN)
);

CREATE TABLE Course (
    CourseNumber INT PRIMARY KEY,                -- Unique identifier for course
    Title VARCHAR(100),                          -- Course title
    Textbook VARCHAR(100),                       -- Textbook name
    Units INT NOT NULL,                          -- Number of units
    DeptNumber INT,                              -- Department offering the course
    FOREIGN KEY (DeptNumber) REFERENCES Department(DeptNumber)
);

CREATE TABLE Section (
    CourseNumber INT,                            -- Course this section belongs to
    SectionNumber INT,                           -- Unique section number within a course
    StartTime TIME,                              -- Start time of the section
    EndTime TIME,                                -- End time of the section
    NumberSeats INT,                             -- Number of seats available
    Classroom VARCHAR(50),                       -- Classroom location
    ProfSSN CHAR(9),                             -- Professor teaching the section
    PRIMARY KEY (CourseNumber, SectionNumber),   -- Composite primary key
    FOREIGN KEY (CourseNumber) REFERENCES Course(CourseNumber),
    FOREIGN KEY (ProfSSN) REFERENCES Professor(SSN)
);

CREATE TABLE Student (
    CWID CHAR(9) PRIMARY KEY,                    -- Campus-wide ID as the primary key
    Fname VARCHAR(50) NOT NULL,                  -- First name
    Lname VARCHAR(50) NOT NULL,                  -- Last name
    Address VARCHAR(100),                        -- Student address
    SPhone CHAR(10)                              -- Student phone number
);

CREATE TABLE EnrollmentRecords (
    CWID CHAR(9),                                -- Student ID (foreign key)
    CourseNumber INT,                            -- Course number (foreign key)
    SectionNumber INT,                           -- Section number (foreign key)
    Grade CHAR(2),                               -- Grade (e.g., A, B, C)
    PRIMARY KEY (CWID, CourseNumber, SectionNumber),
    FOREIGN KEY (CWID) REFERENCES Student(CWID),
    FOREIGN KEY (CourseNumber, SectionNumber) REFERENCES Section(CourseNumber, SectionNumber)
);

CREATE TABLE SectionMeetingDays (
    CourseNumber INT,                            -- Course number (foreign key)
    SectionNumber INT,                           -- Section number (foreign key)
    Day VARCHAR(10),                             -- Day (e.g., Monday, Tuesday)
    PRIMARY KEY (CourseNumber, SectionNumber, Day),
    FOREIGN KEY (CourseNumber, SectionNumber) REFERENCES Section(CourseNumber, SectionNumber)
);

CREATE TABLE ProfessorDegrees (
    ProfSSN CHAR(9),                             -- Professor SSN (foreign key)
    Degree VARCHAR(50),                          -- Degree (e.g., PhD, MSc)
    PRIMARY KEY (ProfSSN, Degree),               -- Composite primary key
    FOREIGN KEY (ProfSSN) REFERENCES Professor(SSN)
);

CREATE TABLE Majors (
    CWID CHAR(9),                                -- Student ID (foreign key)
    DeptNumber INT,                              -- Department ID (foreign key)
    PRIMARY KEY (CWID, DeptNumber),
    FOREIGN KEY (CWID) REFERENCES Student(CWID),
    FOREIGN KEY (DeptNumber) REFERENCES Department(DeptNumber)
);

CREATE TABLE Minors (
    CWID CHAR(9),                                -- Student ID (foreign key)
    DeptNumber INT,                              -- Department ID (foreign key)
    PRIMARY KEY (CWID, DeptNumber),
    FOREIGN KEY (CWID) REFERENCES Student(CWID),
    FOREIGN KEY (DeptNumber) REFERENCES Department(DeptNumber)
);
