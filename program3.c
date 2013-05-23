/**
 * Task 2
 * Ip lookup in database
 * 
 * call : ./program3 --database database.db 10.1.2.3
 * 
 * compile with : gcc program3.c -o program3 -lsqlite3
 */
#include <stdio.h>
#include <string.h>
#include <sqlite3.h>

int main(int argc, char *argv[])
{
    if (argv[1] && argv[2] && argv[3]) 
	{
		if (strcmp(argv[1],"--database")==0) //IP address lookup
		{
			sqlite3 *sqliteConnection;
			sqlite3_stmt *query;
			
			long ip;
			int count=0;
			int a1,a2,a3,a4=-1;
			sscanf(argv[3], "%d.%d.%d.%d",&a1,&a2,&a3,&a4); //get ip address from params
			if(a1 != -1 && a2 != -1 && a3 != -1 && a4 != -1) //if error in ip, end
			{
				ip = (a1 << 24) | (a2 << 16) | (a3 << 8) | a4; //convert ip to long
				
				if (sqlite3_open_v2(argv[2], &sqliteConnection,SQLITE_OPEN_READONLY, NULL) == SQLITE_OK) //only for read open connection
				{
					sqlite3_prepare_v2(sqliteConnection, "SELECT * FROM ip_address WHERE (ip_number & (~((1<<(32-mask))-1))) = (? & (~((1<<(32-mask))-1)));", -1, &query, NULL); //create connection
					sqlite3_bind_int(query, 1, ip); //bind param
				 
					while (sqlite3_step(query) == SQLITE_ROW) { //print results
						printf("%s/%s\n",sqlite3_column_text(query, 1),sqlite3_column_text(query, 2)); 
						count++;
					}
				   if(count==0)
						printf("No result found! \n"); 
				}
				else
				{
					printf("Can not open database \n");
				}
			}
		}
		else
		{
			printf("Incorrect format to execute program, correct format is : ./program3 --database database.db 10.1.2.3 ");
		}
	}
	else
	{
		printf("Not enought params for execute script \n");
	}
}
