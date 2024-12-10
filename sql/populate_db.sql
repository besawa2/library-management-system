use library_management_system;

DELETE FROM penalties;
DELETE FROM reservations;
DELETE FROM rentals;
DELETE FROM user_profile;
DELETE FROM staff;
DELETE FROM events;
DELETE FROM user;
DELETE FROM books;

INSERT INTO user (UserID, username, password, Name) VALUES (1, 'user1', 'password1', 'John Doe');
INSERT INTO user (UserID, username, password, Name) VALUES (2, 'user2', 'password2', 'Jane Doe');
INSERT INTO user (UserID, username, password, Name) VALUES (3, 'user3', 'password3', 'Alice Johnson');
INSERT INTO user (UserID, username, password, Name) VALUES (4, 'user4', 'password4', 'Michael Green');
INSERT INTO user (UserID, username, password, Name) VALUES (5, 'user5', 'password5', 'Linda White');

INSERT INTO books VALUES (1, 'To Kill a Mockingbird', 'Harper Lee', 'Fiction', '9780060935467', '1960-07-11', 'J.B. Lippincott & Co.', 'https://example.com/bookcovers/tokillamockingbird.jpg');
INSERT INTO books VALUES (2, 'The Catcher in the Rye', 'J.D. Salinger', 'Fiction', '9780316769488', '1951-07-16', 'Little, Brown and Company', 'https://example.com/bookcovers/catcherintherye.jpg');
INSERT INTO books VALUES (3, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', '9780743273565', '1925-04-10', 'Scribner', 'https://example.com/bookcovers/greatgatsby.jpg');

INSERT INTO books VALUES (4, '1984', 'George Orwell', 'Dystopian', '9780451524935', '1949-06-08', 'Secker & Warburg', 'https://example.com/bookcovers/1984.jpg');
INSERT INTO books VALUES (5, 'Brave New World', 'Aldous Huxley', 'Dystopian', '9780060850524', '1932-08-31', 'Harper & Brothers', 'https://example.com/bookcovers/bravenewworld.jpg');
INSERT INTO books VALUES (6, 'Fahrenheit 451', 'Ray Bradbury', 'Dystopian', '9781451673319', '1953-10-19', 'Ballantine Books', 'https://example.com/bookcovers/fahrenheit451.jpg');

INSERT INTO books VALUES (7, 'Moby-Dick', 'Herman Melville', 'Adventure', '9781503280786', '1851-10-18', 'Harper & Brothers', 'https://example.com/bookcovers/mobydick.jpg');
INSERT INTO books VALUES (8, 'The Hobbit', 'J.R.R. Tolkien', 'Adventure', '9780547928227', '1937-09-21', 'George Allen & Unwin', 'https://example.com/bookcovers/thehobbit.jpg');
INSERT INTO books VALUES (9, 'The Alchemist', 'Paulo Coelho', 'Adventure', '9780062315007', '1988-05-01', 'HarperOne', 'https://example.com/bookcovers/thealchemist.jpg');

INSERT INTO books VALUES (10, 'Pride and Prejudice', 'Jane Austen', 'Romance', '9780141439518', '1813-01-28', 'T. Egerton', 'https://example.com/bookcovers/prideandprejudice.jpg');
INSERT INTO books VALUES (11, 'The Fault in Our Stars', 'John Green', 'Romance', '9780525478812', '2012-01-10', 'Dutton Books', 'https://example.com/bookcovers/thefaultinourstars.jpg');
INSERT INTO books VALUES (12, 'Emma', 'Jane Austen', 'Romance', '9780141439501', '1815-12-23', 'T. Egerton', 'https://example.com/bookcovers/emma.jpg');

INSERT INTO books VALUES (13, 'The Shining', 'Stephen King', 'Horror', '9780385121675', '1977-01-28', 'Doubleday', 'https://example.com/bookcovers/theshining.jpg');
INSERT INTO books VALUES (14, 'Dracula', 'Bram Stoker', 'Horror', '9780141439842', '1897-05-26', 'Penguin Classics', 'https://example.com/bookcovers/dracula.jpg');
INSERT INTO books VALUES (15, 'It', 'Stephen King', 'Horror', '9780452281841', '1986-09-15', 'Viking Press', 'https://example.com/bookcovers/it.jpg');

INSERT INTO books VALUES (16, 'Of Mice and Men', 'John Steinbeck', 'Fiction', '9780140177398', '1937-02-06', 'Penguin Books', 'https://example.com/bookcovers/ofmiceandmen.jpg');
INSERT INTO books VALUES (17, 'The Road', 'Cormac McCarthy', 'Fiction', '9780307387899', '2006-09-26', 'Alfred A. Knopf', 'https://example.com/bookcovers/theroad.jpg');
INSERT INTO books VALUES (18, 'Beloved', 'Toni Morrison', 'Fiction', '9781400033416', '1987-09-16', 'Knopf', 'https://example.com/bookcovers/beloved.jpg');
INSERT INTO books VALUES (19, 'The Book Thief', 'Markus Zusak', 'Fiction', '9780375842207', '2005-09-01', 'Knopf Books', 'https://example.com/bookcovers/thebookthief.jpg');
INSERT INTO books VALUES (20, 'Life of Pi', 'Yann Martel', 'Fiction', '9780156027328', '2001-09-11', 'Knopf Canada', 'https://example.com/bookcovers/lifeofpi.jpg');
INSERT INTO books VALUES (21, 'The Kite Runner', 'Khaled Hosseini', 'Fiction', '9781594631931', '2003-05-29', 'Riverhead Books', 'https://example.com/bookcovers/thekiterunner.jpg');
INSERT INTO books VALUES (22, 'A Thousand Splendid Suns', 'Khaled Hosseini', 'Fiction', '9781594483859', '2007-05-22', 'Riverhead Books', 'https://example.com/bookcovers/athousandsplendidsuns.jpg');
INSERT INTO books VALUES (23, 'The Help', 'Kathryn Stockett', 'Fiction', '9780399155345', '2009-02-10', 'Amy Einhorn Books', 'https://example.com/bookcovers/thehelp.jpg');
INSERT INTO books VALUES (24, 'The Night Circus', 'Erin Morgenstern', 'Fiction', '9780307744432', '2011-09-13', 'Doubleday', 'https://example.com/bookcovers/thenightcircus.jpg');

INSERT INTO staff VALUES (1, 'Linda Green', '123 Library Ave, Springfield', 'Librarian', 45000.00);
INSERT INTO staff VALUES (2, 'Tom Carter', '456 Paper St, Shelbyville', 'Assistant Librarian', 35000.00);
INSERT INTO staff VALUES (3, 'Sarah Miles', '789 Book Ln, Capital City', 'Event Coordinator', 40000.00);
INSERT INTO staff VALUES (4, 'Michael Reid', '101 Stack Rd, Ogdenville', 'Archivist', 42000.00);
INSERT INTO staff VALUES (5, 'Emily White', '202 Story Ct, North Haverbrook', 'Library Technician', 30000.00);

INSERT INTO events VALUES (1, '2024-03-01', '2024-03-03', 'Library Hall A', 'Spring Book Fair');
INSERT INTO events VALUES (2, '2024-05-15', '2024-05-15', 'Library Conference Room', 'Author Meet-and-Greet with Harper Lee');
INSERT INTO events VALUES (3, '2024-07-20', '2024-07-20', 'Library Main Hall', 'Summer Reading Challenge Finale');
INSERT INTO events VALUES (4, '2024-10-10', '2024-10-10', 'Library Hall B', 'Fall Lecture Series: Classics Revisited');
INSERT INTO events VALUES (5, '2024-12-05', '2024-12-05', 'Library Auditorium', 'Holiday Book Drive Gala');

INSERT INTO rentals VALUES (1, 1, '2024-02-01', '2024-02-15', '2024-02-10', 1);
INSERT INTO rentals VALUES (2, 2, '2024-03-01', '2024-03-15', '2024-03-12', 2);
INSERT INTO rentals VALUES (3, 3, '2024-04-01', '2024-04-15', '2024-04-14', 3);

INSERT INTO reservations VALUES (1, 1, 1, 1, '2024-02-01', '2024-02-15');
INSERT INTO reservations VALUES (2, 2, 2, 2, '2024-03-01', '2024-03-15');
INSERT INTO reservations VALUES (3, 3, 3, 3, '2024-04-01', '2024-04-15');

INSERT INTO penalties VALUES (1, 1, 2, '2024-03-16', 5.00);
INSERT INTO penalties VALUES (2, 3, 3, '2024-04-16', 10.00);
INSERT INTO penalties VALUES (3, 4, 4, '2024-05-16', 2.50);
INSERT INTO penalties VALUES (4, 2, 5, '2024-06-16', 7.50);
INSERT INTO penalties VALUES (5, 5, 1, '2024-07-16', 3.00);