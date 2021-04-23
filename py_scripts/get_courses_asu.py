

from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.by import By
import time
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


class ASUCourse:
    def __init__(self,dict):
        self.course_code = dict["course_code"]
        self.course = dict["course"]
        self.descr = dict["descr"]
        self.professor = dict["professor"]
        self.semester = dict["semester"]


def get_tbl_info():
    try:
        WebDriverWait(driver, 8).until(EC.presence_of_element_located((By.ID, "CatalogList")))
        table = driver.find_element_by_id('CatalogList')
        trs = table.find_elements(By.TAG_NAME, "tr")
        trs.pop(0)
        for row in trs:
            course = row.find_element(By.CLASS_NAME, "subjectNumberColumnValue").text
            course = (course[:12]) if len(course) > 12 else course
            descr = row.find_element(By.CLASS_NAME, "titleColumnValue").text
            descr = (descr[:97] + '..') if len(descr) > 97 else descr
            instructor = row.find_element(By.CLASS_NAME, "instructorListColumnValue").text
            instructor = (instructor[:97] + '..') if len(instructor) > 97 else instructor
            course_code = key + str(course).replace(" ","").replace("-","") + str(instructor[0:6]).replace(",","").replace(" ","")
            print(course_code + " - " + course + " - " + descr + " (" + instructor + ") - " + key)
            new_class = {
                "course_code": course_code,
                "course": course,
                "descr": descr,
                "professor": instructor,
                "semester": key
            }
            courses.append(ASUCourse(new_class))
    except(Exception):
        return


def save_courses():

    conn.execute('TRUNCATE TABLE courses')
    mydb.commit()

    query = 'INSERT INTO courses (course_code, course, descr, professor, semester) VALUES '
    comma = ""
    added = []
    for val in courses:
        if val.course_code not in added:
            added.append(val.course_code)
            print('("' + val.course_code + '","' + val.course + '","' + val.descr + '","' + val.professor + '","' + val.semester + '")')
            query += comma
            query += '("' + val.course_code + '","' + val.course + '","' + val.descr + '","' + val.professor + '","' + val.semester + '")'
            comma = ", "
    conn.execute(query)
    mydb.commit()
    return


def check_link_exists(num):
    id = "Any_23_" + str(num)
    try:
        link = driver.find_element(By.ID, id)
        link.click()
        time.sleep(5)
    except Exception:
        return False
    return True


terms = dict()
terms["SP21"] = 2211
terms["SU21"] = 2214
terms["FA21"] = 2217

url ='https://webapp4.asu.edu/catalog/classlist?t=2217&s=CSE&hon=F&promod=F&e=open&page=1'
driver = webdriver.Chrome("/usr/lib/chromium-browser/chromedriver", options=chrome_options)
#driver = webdriver.Chrome(executable_path="chromedriver.exe")

q1 = 'SELECT subject FROM subjects' # WHERE subject="IEE"'
conn.execute(q1)
result = conn.fetchall()

courses = []
try:
    for key in terms:
        print("GRABBING " + str(key) + " DATA")
        for row in result:
            subject = row[0]
            url = 'https://webapp4.asu.edu/catalog/classlist?t='+str(terms[key])+'&s='+str(subject)+'&hon=F&promod=F&e=all&page=1'
            driver.get(url)
            time.sleep(5)
            print("******" +subject+ " - PAGE 0 INFORMATION******")
            get_tbl_info()
            for x in range(10):
                if check_link_exists(x):
                    print("******PAGE " + str(x + 1) + " INFORMATION******")
                    get_tbl_info()
    save_courses()
    print("Page is ready!")
except TimeoutException:
    print("Couldn't load page")

driver.close()

