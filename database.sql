-- Table to store all professors and their details, including personal information and contact details.
CREATE TABLE Professor (
    SSN CHAR(9) PRIMARY KEY,                     -- Unique identifier for each professor
    ProfName VARCHAR(50) NOT NULL,               -- Full name of the professor
    Sex CHAR(1),                                 -- Gender (M/F/Other)
    Title VARCHAR(50),                           -- Academic or professional title (e.g., Dr., Prof.)
    Salary DECIMAL(10, 2),                       -- Salary (in USD)
    StreetAddress VARCHAR(100),                  -- Residential address
    City VARCHAR(50),                            -- City of residence
    State CHAR(2),                               -- State of residence (2-letter code)
    ZIP CHAR(5),                                 -- ZIP code
    AreaCode CHAR(3),                            -- Area code of phone number
    Digits CHAR(7)                               -- Phone number digits (excluding area code)
);

-- Table to store information about university departments, including department name and chair.
CREATE TABLE Department (
    DeptNumber INT PRIMARY KEY,                  -- Unique identifier for each department
    DeptName VARCHAR(50) NOT NULL,               -- Name of the department
    DeptPhone CHAR(10),                          -- Contact number of the department
    OfficeLocation VARCHAR(50),                  -- Office location for the department
    ChairSSN CHAR(9),                            -- SSN of the professor serving as department chair
    FOREIGN KEY (ChairSSN) REFERENCES Professor(SSN) -- Links to the Professor table
);

-- Table to store information about courses offered by the university.
CREATE TABLE Course (
    CourseNumber INT PRIMARY KEY,                -- Unique identifier for each course
    Title VARCHAR(100),                          -- Title of the course
    Textbook VARCHAR(100),                       -- Name of the textbook used
    Units INT NOT NULL,                          -- Number of units for the course (must be greater than 0)
    DeptNumber INT,                              -- Department offering the course
    FOREIGN KEY (DeptNumber) REFERENCES Department(DeptNumber) -- Links to the Department table
);

-- Table to store sections for courses, including their schedule, classroom, and assigned professor.
CREATE TABLE Section (
    CourseNumber INT,                            -- Course associated with this section
    SectionNumber INT,                           -- Unique section number for the course
    StartTime TIME,                              -- Start time for the section
    EndTime TIME,                                -- End time for the section
    NumberSeats INT,                             -- Number of available seats in the section
    Classroom VARCHAR(50),                       -- Classroom location
    ProfSSN CHAR(9),                             -- SSN of the professor teaching the section
    PRIMARY KEY (CourseNumber, SectionNumber),   -- Composite primary key to uniquely identify a section
    FOREIGN KEY (CourseNumber) REFERENCES Course(CourseNumber), -- Links to the Course table
    FOREIGN KEY (ProfSSN) REFERENCES Professor(SSN) -- Links to the Professor table
);

-- Table to store information about students, including their personal details and contact information.
CREATE TABLE Student (
    CWID CHAR(9) PRIMARY KEY,                    -- Unique campus-wide identifier for each student
    Fname VARCHAR(50) NOT NULL,                  -- First name of the student
    Lname VARCHAR(50) NOT NULL,                  -- Last name of the student
    Address VARCHAR(100),                        -- Residential address of the student
    SPhone CHAR(10)                              -- Student phone number
);

-- Table to store enrollment records, linking students to specific course sections.
CREATE TABLE EnrollmentRecords (
    CWID CHAR(9),                                -- Student's campus-wide ID (foreign key)
    CourseNumber INT,                            -- Course number (foreign key)
    SectionNumber INT,                           -- Section number (foreign key)
    Grade CHAR(3),                               -- Grade received by the student (e.g., A, B, C)
    PRIMARY KEY (CWID, CourseNumber, SectionNumber), -- Composite primary key for unique enrollment records
    FOREIGN KEY (CWID) REFERENCES Student(CWID), -- Links to the Student table
    FOREIGN KEY (CourseNumber, SectionNumber) REFERENCES Section(CourseNumber, SectionNumber) -- Links to the Section table
);

-- Table to store the meeting days for each section.
CREATE TABLE SectionMeetingDays (
    CourseNumber INT,                            -- Course number (foreign key)
    SectionNumber INT,                           -- Section number (foreign key)
    Day VARCHAR(10),                             -- Day of the week (e.g., Monday, Tuesday)
    PRIMARY KEY (CourseNumber, SectionNumber, Day), -- Composite primary key for unique meeting days
    FOREIGN KEY (CourseNumber, SectionNumber) REFERENCES Section(CourseNumber, SectionNumber) -- Links to the Section table
);

-- Table to store academic degrees obtained by professors.
CREATE TABLE ProfessorDegrees (
    ProfSSN CHAR(9),                             -- SSN of the professor (foreign key)
    Degree VARCHAR(50),                          -- Academic degree (e.g., PhD, MSc)
    PRIMARY KEY (ProfSSN, Degree),               -- Composite primary key for unique degree records
    FOREIGN KEY (ProfSSN) REFERENCES Professor(SSN) -- Links to the Professor table
);

-- Table to store the primary major of each student.
CREATE TABLE Majors (
    CWID CHAR(9),                                -- Student's campus-wide ID (foreign key)
    DeptNumber INT,                              -- Department offering the major (foreign key)
    PRIMARY KEY (CWID, DeptNumber),              -- Composite primary key for unique student-major pairs
    FOREIGN KEY (CWID) REFERENCES Student(CWID), -- Links to the Student table
    FOREIGN KEY (DeptNumber) REFERENCES Department(DeptNumber) -- Links to the Department table
);

-- Table to store the minor of each student, if applicable.
CREATE TABLE Minors (
    CWID CHAR(9),                                -- Student's campus-wide ID (foreign key)
    DeptNumber INT,                              -- Department offering the minor (foreign key)
    PRIMARY KEY (CWID, DeptNumber),              -- Composite primary key for unique student-minor pairs
    FOREIGN KEY (CWID) REFERENCES Student(CWID), -- Links to the Student table
    FOREIGN KEY (DeptNumber) REFERENCES Department(DeptNumber) -- Links to the Department table
);
