Project Proposal
CS3380
March 11, 2015

Project Members:
Jordan Backes
Ethan Currier
Matthew Iskra
Jackson Nowotny
Nolan Rackers

Topic: 
Facial recognition and estimated demographics to enhance gross customer analysis in commercial environments.

Description:
	Our project involves creating a dataset based upon facial recognition and demographic information, and using various tools to analyze the data for use in a commercial or enterprise setting. The end product will be something (a web app) that can be used by companies or schools to gather useful information about event attendance and demographics via placed or existing cameras. The dataset will be gathered in real time via facial recognition software from a camera. The camera will detect faces using OpenCV and Face++, and gather various demographic data by using Face++'s estimation algorithms on the detected faces. The software will then populate a database with the gathered data that will include: face id, approximate age, gender, location, and time of recognition. It's important to note that no personal information will be stored, as each face will be given an id, and all of the demographic information will be only an estimate. Another important note is that no footage will be stored, only data related to detected faces. As a result of the particularity of our data, no prepared dataset will be used; rather, our camera will be set up in a popular and appropriate setting (with approval), such as a classroom or restaurant, to gather test data. The database will gather and store data in real time, and will be able to make comparisons with existing data. A website and webapp will be created to view and process the data with various functions. Current functions that we plan to implement include: searching by any of the attributes, various querying for comparisons, and obtaining information broken down by various data combinations (such as age breakdown for a specific time), generating graphs and charts of certain data (for example: number of people detected over time), and setting up email notifications of specified data (such as an end-of-day demographic breakdown). The data recorded will also be organized by user (universities, restaurants, etc...), so a login system will be implemented on the website in which different users have access to their set of data, but only their data. 

Purpose:
	The purpose of our project is to provide a tool to gather valuable demographic information. Such information could have various benefits in commercial, and even educational use. For example, restaurants, department stores, and other businesses can gather information such as gender and age for market research, and classrooms and businesses can gather attendance information. (With the current state of facial recognition, the data gathered should only be used as an estimate, and not for things such as actually taking attendance)

Technologies:
•	Postgres to store data.
•	Webserver to host website.
•	PHP and HTML (and possibly JavaScript) to implement website and webapp to view and process data.
•	Custom software using OpenCV and Face++ to process input and convert data to an appropriate form for storage.
•	Camera to gather input.

Meetings:
Group meetings to discuss progress and future plans are planned for Sunday afternoons. Lab session time will also be used to discuss the project.

Steps to completion:
1)	Map out database layout
2)	Create and setup database 
3)	Create software using Face++ API to populate database based on input
4)	Populate dataset
5)	Setup webserver and website to view data
6)	Implement login functionality
7)	Add various data processing functionalities to website
8)	Add graph generating functionalities to website
9)	Add email notification functionalities to website
10)	Receive A+

Division of labor:
•	Everyone: Map out database layout, create and setup database, collect footage to populate the dataset
•	Jordan:
•	Ethan: Website structure/development
•	Matthew:
•	Jackson: Create software using Face++ API to populate database based on input 
•	Nolan: Add various data processing functionalities to website

Note: Since there are 3 major parts of our project (website, facial recognition, databse), and because 5%3 != 0, we will likely divide into teams, with some more specific parts being completed over time by many people.
