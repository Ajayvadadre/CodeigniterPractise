import csv
import random

# List of Indian first names
first_names = ["Rahul", "Amit", "Rohan", "Sohan", "Karan", "Aryan", "Vivek", "Sachin", "Rajesh", "Suresh", "Mahesh", "Dinesh", "Rakesh", "Mukesh", "Narendra", "Rajendra", "Vijay", "Sanjay", "Sunil", "Anil", "Kumar", "Raj", "Sandeep", "Deepak", "Ravi", "Praveen", "Nitin", "Tarun", "Rahul", "Amit", "Rohan", "Sohan", "Karan", "Aryan", "Vivek", "Sachin", "Rajesh", "Suresh", "Mahesh", "Dinesh", "Rakesh", "Mukesh"]

# List of Indian last names
last_names = ["Sharma", "Gupta", "Singh", "Kumar", "Jain", "Mehta", "Patel", "Rao", "Reddy", "Naidu", "Sinha", "Chandra", "Das", "Mishra", "Verma", "Agarwal", "Joshi", "Shah", "Goyal", "Bansal", "Kapoor", "Khanna", "Malhotra", "Saxena", "Tiwari", "Trivedi", "Upadhyay", "Vyas", "Wadhwa", "Yadav"]

# List of email domains
email_domains = ["@gmail.com", "@yahoo.com", "@hotmail.com", "@outlook.com", "@aol.com", "@rediffmail.com", "@indiatimes.com", "@hotmail.co.uk", "@yahoo.co.uk", "@gmail.co.uk"]

# Generate 50 Indian names, email addresses, and ages
data = []
for i in range(50):
    first_name = random.choice(first_names)
    last_name = random.choice(last_names)
    email_domain = random.choice(email_domains)
    email = f"{first_name.lower()}{last_name.lower()}{email_domain}"
    age = random.randint(18, 65)  # generate a random age between 18 and 65
    data.append([f"{first_name} {last_name}", email, age])

# Write data to CSV file
with open('indian_names_emails_ages.csv', 'w', newline='') as csvfile:
    writer = csv.writer(csvfile)
    writer.writerow(["name", "email", "age"])  # header
    writer.writerows(data)