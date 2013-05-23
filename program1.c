/* Task 1
 * Program for create terminal table
 * call : ./program1 file.txt
 */

#include <stdio.h>
#include <string.h>

int main(int argc, char *argv[])
{
    if (argv[1]) 
	{
		terminalTable(argv[1]);	
	}
	else
	{
		printf("Not enought params for execute script \n");
	}
}
/**
* Function render table in terminal
*/
int terminalTable(char *fileName)
{
	FILE *dataFile;
	char lineNow[100]={' '}; //maximal line in text is 100
	char lineBefor[100]={' '};
	
	int lenghtNow=0;
	int lenghtBefor=0;
	
	dataFile = fopen(fileName, "r");
	if (dataFile == NULL) {
		printf("File %s not found",fileName);
	
	}
	else
	{
		 while(fgets(lineNow, 100, dataFile) != NULL) //read content of file, line by line
		 {
			lenghtNow=strlen(lineNow);
			lenghtBefor=strlen(lineBefor);
			
			//if next line have less caracter, add empty caracter
			while(lenghtNow<lenghtBefor)
			{
				lineNow[lenghtNow]=' ';
				lenghtNow++;
			}
			
			renderRowSimbol(lineNow,lineBefor); //render first simbol line
			renderRowValue(lineNow,lineBefor);  //render value line
			stpcpy(lineBefor,lineNow);
			
		 }
		 renderRowSimbol(lineNow,lineNow);  //on end, render last simbol line
		 
	}

}

/**
* Function render row of simbols " + or - " for terminal table
* @param string $lineNow, string with value of one line
* @param string $lineBefor, string with value of line befor
*/
renderRowSimbol(char *lineNow,char *lineBefor)
{
	int lenght=strlen(lineNow);
	int key=0;
	while(key<lenght)
	{
		if(key%2 == 0)
		{
			if(lineNow[key]!=' ')
			{
				//if need add +
				if((key-2)<0 || ( lineNow[key-2]==' ' && (lineBefor[key-2]==' ' || lineBefor[key-2]==0 )) || ( lineNow[key-2]==' ' && (lineBefor[key-2]==' '  || lineBefor[key-2]==0)) ) 
				{
					printf("+");
				}
				printf("---+");
			}
			else
			{
				if(lineBefor[key]!=' ' && lineBefor[key]!=0)
				{
					//if need add +
					if(((key-2)<0) || lineBefor[key-2]==' ') 
					{ 
						printf("+");
					} 
					printf("---+");
				}
				else
				{
					//if need add " "
					if((key-2)<0 || ( lineNow[key-2]==' ' && (lineBefor[key-2]==' ' || lineBefor[key-2]==0 )) || ( lineNow[key-2]==' ' && (lineBefor[key-2]==' '  || lineBefor[key-2]==0)) ) 
					{
						printf(" ");
					}
					printf("   ");
				}
			}
		}
		key++;	
	}
	printf("\n");
}

/**
* Function render row of simbols " + or - " for terminal table
* @param string $lineNow, string with value of one line
* @param string $lineBefor, string with value of line befor
*/
renderRowValue(char *lineNow,char *lineBefor)
{
	int lenght=strlen(lineNow);
	int key=0;
	while(key<lenght)
	{
		if(key%2 == 0) //print only every second caracter
		{
			if(lineNow[key]!=' ') //for every element of line check is space or other caracter and print simbols 
			{
				if((key-2)<0 || lineNow[key-2]==' ')
				{
					printf("|");
				}
				printf(" %c |",lineNow[key]);
			}
			else
			{
				if((key-2)<0 || lineNow[key-2]==' ')
				{
					printf(" ");
				}
				printf("   ");
			}
		}
		key++;
	}
	printf("\n");
}
