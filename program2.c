/**
 * Task 2
 * Ip lookup in file
 * 
 * call : ./program2 --database database.txt 10.1.2.3
 */
 
#include <stdio.h>
#include <string.h>

int main(int argc, char *argv[])
{
    if (argv[1] && argv[2] && argv[3]) 
	{
		if (strcmp(argv[1],"--database")==0) //IP address lookup
		{
			//read file
			FILE *dataFile;
			char line[18]; //maximal line in text is 18
			dataFile = fopen(argv[2], "r");
			int a1,a2,a3,a4=-1;
			int m; 
			int count=0;
			
			long ip;
			long range;
			long mask;
			
			if (dataFile == NULL) {
				printf("File %s not found",argv[2]);
			}
			else
			{
				sscanf(argv[3], "%d.%d.%d.%d",&a1,&a2,&a3,&a4); 
				if(a1 != -1 && a2 != -1 && a3 != -1 && a4 != -1)
				{
					ip = (a1 << 24) | (a2 << 16) | (a3 << 8) | a4;
					
					while(fgets(line, 18, dataFile) != NULL) //read content of file, line by line
					{
						a1=a2=a3=a4=mask=-1;
						sscanf(line, "%d.%d.%d.%d/%d",&a1,&a2,&a3,&a4,&m);
						if(a1 != -1 && a2 != -1 && a3 != -1 && a4 != -1 && m != -1) //if is -1 some error is ip range definition
						{
							range = (a1 << 24) | (a2 << 16) | (a3 << 8) | a4;
							mask = ~ ((1 << (32-m)) - 1); 
							if( (ip & mask) == (range & mask) )
							{
								printf("%d.%d.%d.%d/%d\n",a1,a2,a3,a4,m);
								count++;
							}
							//convert
						}
						
					}
					if(count==0)
						printf("No result found! \n");
				}
				else
				{
					printf("Incorect format of ip address: %s \n",argv[3]);
				}
				
				
			}
		
		}
		else
		{
			printf("Incorrect format to execute program, correct format is : ./program2 --database database.txt 10.1.2.3 ");
		}
	}
	else
	{
		printf("Not enought params for execute script \n");
	}
}
