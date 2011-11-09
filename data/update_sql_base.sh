#!/bin/bash
# a simple bash script for retrieving the unit test fixture from our development database
# Zend Framework application configuration
CONFIG_FILE="../application/configs/application.ini"
# Fixtures - could be read from application.ini but ... I was lazy
FIXTURE_FILE="fixture.sql"
CREATE_VIEWS_FILE="create-views.sql"
CREATE_TABLES_FILE="create-tables.sql"
DATE=`date`

# read configuration value from application.ini
function read_application_ini_value() {
	SECTION=$1
	PARAM=$2
	IN_SECTION=0

	cat $CONFIG_FILE | while read line; do
		if [ $IN_SECTION -eq "1" ]; then
			if [[ $line =~ $PARAM ]]; then
				echo `echo $line | awk 'BEGIN { FS = "=" } ; {print $2}'`
				return 0;
 			fi
		fi	
		if [[ $line =~ "[$SECTION" ]]; then
			IN_SECTION=1
		fi
	done
}

MYSQL_USER=`read_application_ini_value "production" "resources.db.params.username"`
MYSQL_PASSWORD=`read_application_ini_value "production" "resources.db.params.password"`
MYSQL_DATABASE=`read_application_ini_value "production" "resources.db.params.dbname"`

if [ -e $FIXTURE_FILE ]; then
	rm $FIXTURE_FILE
fi


# create fixture
echo "Dumping fixture from $MYSQL_DATABASE"
echo "-- fixture dumped on $DATE" >> $FIXTURE_FILE
mysqldump -t -K -u $MYSQL_USER -c -p$MYSQL_PASSWORD $MYSQL_DATABASE > $FIXTURE_FILE

# read views
VIEWS=`mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE --execute="SHOW FULL TABLES WHERE Table_type='VIEW'" | grep -e "VIEW" | awk '{print $1}'`
# read tables
TABLES=`mysql -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE --execute="SHOW FULL TABLES WHERE Table_type='BASE TABLE'" | grep -e "BASE TABLE" | awk '{print $1}'`

if [ -e $CREATE_VIEWS_FILE ]; then
	rm $CREATE_VIEWS_FILE;
fi;

echo "-- views dumped on $DATE" >> $CREATE_VIEWS_FILE

for view in $VIEWS
do
	echo "Dumping view $MYSQL_DATABASE.$view"
	mysqldump -d -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE $view >> $CREATE_VIEWS_FILE
done

if [ -e $CREATE_TABLES_FILE ]; then
	rm $CREATE_TABLES_FILE;
fi;

echo "-- tables dumped on $DATE" >> $CREATE_VIEWS_FILE

for table in $TABLES
do
	echo "Dumping table $MYSQL_DATABASE.$table"
	mysqldump -d -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE $table >> $CREATE_TABLES_FILE
done

