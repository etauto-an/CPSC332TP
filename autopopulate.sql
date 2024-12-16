    -- Populate the Professor table
    INSERT INTO Professor (SSN, ProfName, Sex, Title, Salary, StreetAddress, City, State, ZIP, AreaCode, Digits)
    VALUES
    ('123456789', 'John Doe', 'M', 'Professor', 80000.00, '123 Main St', 'Springfield', 'CA', '90210', '123', '4567890'),
    ('987654321', 'Jane Smith', 'F', 'Lecturer', 70000.00, '456 Elm St', 'Riverside', 'CA', '92501', '951', '6547890'),
    ('567890123', 'Robert Brown', 'M', 'Assistant Professor', 75000.00, '789 Maple St', 'Los Angeles', 'CA', '90001', '213', '7890123');

    -- Populate the Department table
    INSERT INTO Department (DeptNumber, DeptName, DeptPhone, OfficeLocation, ChairSSN)
    VALUES
    (1, 'Computer Science', '1234567890', 'Room 101', '123456789'),
    (2, 'Mathematics', '9876543210', 'Room 202', '987654321');

    -- Populate the Course table
    INSERT INTO Course (CourseNumber, Title, Textbook, Units, DeptNumber)
    VALUES
    (101, 'Database Systems', 'Database Concepts', 4, 1),
    (102, 'Linear Algebra', 'Linear Algebra and Its Applications', 3, 2),
    (103, 'Operating Systems', 'Modern Operating Systems', 3, 1),
    (104, 'Calculus I', 'Calculus: Early Transcendentals', 4, 2);

    -- Populate the Section table
    INSERT INTO Section (CourseNumber, SectionNumber, StartTime, EndTime, NumberSeats, Classroom, ProfSSN)
    VALUES
    (101, 1, '09:00:00', '10:15:00', 30, 'Room A', '123456789'),
    (102, 1, '10:30:00', '11:45:00', 25, 'Room B', '987654321'),
    (103, 1, '12:00:00', '13:15:00', 20, 'Room C', '567890123'),
    (104, 1, '13:30:00', '14:45:00', 30, 'Room D', '987654321'),
    (101, 2, '15:00:00', '16:15:00', 30, 'Room A', '123456789'), --missing from prof_sched
    (102, 2, '16:30:00', '17:45:00', 25, 'Room B', '567890123'); --missing from prof_sched

    -- Populate the Student table
    INSERT INTO Student (CWID, Fname, Lname, Address, SPhone)
    VALUES
    ('S1001', 'Alice', 'Brown', '123 Pine St, Riverside, CA', '9511234567'),
    ('S1002', 'Bob', 'Johnson', '456 Oak St, Riverside, CA', '9519876543'),
    ('S1003', 'Charlie', 'Williams', '789 Spruce St, Riverside, CA', '9515432109'),
    ('S1004', 'David', 'Jones', '321 Birch St, Los Angeles, CA', '2131234567'),
    ('S1005', 'Eve', 'Miller', '654 Walnut St, Los Angeles, CA', '2139876543'),
    ('S1006', 'Frank', 'Taylor', '987 Cedar St, Riverside, CA', '9517654321'),
    ('S1007', 'Grace', 'Moore', '159 Maple St, Riverside, CA', '9514321098'),
    ('S1008', 'Hannah', 'Anderson', '753 Elm St, Los Angeles, CA', '2136789054');

    -- Populate the EnrollmentRecords table with a mix of grades
    INSERT INTO EnrollmentRecords (CWID, CourseNumber, SectionNumber, Grade)
    VALUES
    ('S1001', 101, 1, 'A+'),
    ('S1002', 101, 1, 'A'),
    ('S1003', 102, 1, 'A-'),
    ('S1004', 103, 1, 'B+'),
    ('S1005', 104, 1, 'B'),
    ('S1006', 101, 2, 'B-'),
    ('S1007', 102, 2, 'C+'),
    ('S1008', 103, 1, 'C'),
    ('S1001', 104, 1, 'C-'),
    ('S1002', 103, 1, 'D'),
    ('S1003', 101, 2, 'A+'),
    ('S1004', 102, 2, 'A'),
    ('S1005', 101, 1, 'A-'),
    ('S1006', 102, 1, 'B+'),
    ('S1007', 103, 1, 'B'),
    ('S1008', 101, 1, 'B-'),
    ('S1001', 102, 2, 'C+'),
    ('S1002', 104, 1, 'C'),
    ('S1003', 103, 1, 'C-'),
    ('S1004', 101, 2, 'D');

    -- Populate the SectionMeetingDays table
    INSERT INTO SectionMeetingDays (CourseNumber, SectionNumber, Day)
    VALUES
    (101, 1, 'Monday'),
    (101, 1, 'Wednesday'),
    (102, 1, 'Tuesday'),
    (102, 1, 'Thursday'),
    (103, 1, 'Monday'),
    (103, 1, 'Wednesday'),
    (104, 1, 'Tuesday'),
    (104, 1, 'Thursday');

    (101, 2, 'Tuesday'),
    (101, 2, 'Thursday');
    (102, 2, 'Monday'),
    (102, 2, 'Wednesday');


    -- Populate the ProfessorDegrees table
    INSERT INTO ProfessorDegrees (ProfSSN, Degree)
    VALUES
    ('123456789', 'PhD in Computer Science'),
    ('987654321', 'MSc in Mathematics'),
    ('567890123', 'PhD in Electrical Engineering');

    -- Populate the Majors table
    INSERT INTO Majors (CWID, DeptNumber)
    VALUES
    ('S1001', 1),
    ('S1002', 2),
    ('S1003', 1),
    ('S1004', 2),
    ('S1005', 1),
    ('S1006', 1),
    ('S1007', 2),
    ('S1008', 2);

    -- Populate the Minors table
    INSERT INTO Minors (CWID, DeptNumber)
    VALUES
    ('S1001', 2),
    ('S1003', 2),
    ('S1005', 2),
    ('S1007', 1),
    ('S1008', 1);
