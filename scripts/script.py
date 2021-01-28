import csv
import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="root",
  database="buscador_pbu"
)

mycursor = mydb.cursor()

notinserted = 0

with open('../files_uploaded/1.csv', encoding = "ISO-8859-1") as csvarchivo:
    entrada = csv.reader(csvarchivo, delimiter=';')
    for reg in entrada:
        if reg[0] == 'NUM_DOC': continue;
        alc =  True if reg[3] == 'ALCANZADO' else False
        document = "'" + reg[0] + "'"
        sql = "INSERT INTO `users` (`document`, `full_name`, `gender`, `situation`) VALUES ({},{},{},{})".format("'" + reg[0] + "'", "'" + reg[1] + "'", "'" + reg[2] + "'", alc)
        try:
            mycursor.execute(sql)  
            mydb.commit()
        except:
            notinserted += 1


    print(mycursor.rowcount, "record inserted.")
    print(notinserted, "record not inserted.")


