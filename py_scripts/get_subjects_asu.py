

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.by import By
import mysql.connector

chrome_options = Options()
chrome_options.add_argument('--headless')
chrome_options.add_argument('--no-sandbox')
chrome_options.add_argument('--disable-dev-shm-usage')

mydb = mysql.connector.connect(
    host="localhost",
    user="USERNAME",
    password="PASSWORD",
    database="DB"
)
conn = mydb.cursor()

def get_subjects():
    try:
        container = driver.find_element(By.CLASS_NAME, "ac_results")
        options = container.find_elements(By.TAG_NAME, "li")
        for option in options:
            if option.text != "" and option.text != " ":
                subjects.append(option.text)
    except Exception:
        container = driver.find_element(By.CLASS_NAME, "ac_results")
        options = container.find_elements(By.TAG_NAME, "li")
        for option in options:
            if option.text != "" and option.text != " ":
                subjects.append(option.text)


alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z']
subjects = []
url ='https://webapp4.asu.edu/catalog/'
driver = webdriver.Chrome("/usr/lib/chromium-browser/chromedriver", options=chrome_options)
#driver = webdriver.Chrome(executable_path="chromedriver.exe")

try:
    driver.get(url)
    for letter in alphabet:
        subjectBar = driver.find_element(By.ID, "subjectEntry")
        subjectBar.send_keys(letter)
        driver.implicitly_wait(3)
        get_subjects()
        subjectBar.clear()
    #print(subjects)

except TimeoutException:
    print("Couldn't load page")

driver.close()

query = 'TRUNCATE courses;'
conn.execute(query)

query = ' INSERT INTO subjects (subject,descr) VALUES '
comma = ""
for str in subjects:
    query += comma
    sub = str[0:3]
    descr = str[6:]
    query += '("' + sub + '", "' + descr + '")'
    comma = ', '
query += ';'
conn.execute(query)
mydb.commit()
