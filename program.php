#!/usr/bin/php -q
<?php 
    if(count($argv)>1) //check is there params
    {
        if($argv[1]=='--database') //IP address lookup
        {
            if(isset($argv[2]) && isset($argv[3]))
            {
				$inf=pathinfo($argv[2]);
				if($inf["extension"]=="db") 
					print ipLookupFromDatabase($argv[2],$argv[3]);
				else 
					print ipLookupFromFile($argv[2],$argv[3]);
			} 	
			else
				print "Not enought params for execute script"; 
        }
        else //Terminal tables
        {
            print terminalTable($argv[1]); 
        }
    }
    else //only script name
    {
        print "Not enought params for execute script"; 
    }
    print "\n";

	/**
	* Function render table in terminal
	* @param string $file, path to file
	* @return string
	*/
    function terminalTable($file)
    {
        if(file_exists($file)) //check is file path ok
        {
			$resultContent=array(); //all result will be here
			$lineBefor=array();
			
            $fileHandle = fopen($file, "r");
		    $rowCount=0;
            while (!feof($fileHandle)) { //read file, line by line
					$line = fgets($fileHandle);
					$line = str_replace(PHP_EOL,"",$line);
					$columnCount=0;
					$lineNow=array();
					
					//read content of line and convert to array
					for($i=0;$i<strlen($line);$i++)
					{
						if($i%2==0) //get only every second caracter
						{
							$lineNow[$columnCount]=$line[$i];
							$columnCount++;
						}
					}
					//if next line have less caracter, add empty caracter
					if( count($lineNow)<count($lineBefor) )
					{
						while($columnCount<count($lineBefor))
						{	
							$lineNow[$columnCount]=" ";
							$columnCount++;
						}	
					}
				$resultContent[$rowCount]=renderRowSimbol($lineNow,$lineBefor); //render first simbol line
				$rowCount++;
				$resultContent[$rowCount]=renderRowValue($lineNow,$lineBefor); //render value line
				$rowCount++;
				$lineBefor=$lineNow;
            }
			$resultContent[$rowCount]=renderRowSimbol($lineNow,$lineNow); //on end, render last simbol line
            fclose($fileHandle);
			
			return join(PHP_EOL,$resultContent).PHP_EOL; //join result and print 
        }
        else
        {
            return "File '$file' not found";
        }
    }
	
	
	/**
	* Function render row of simbols " + or - " for terminal table
	* @param array $lineNow, array with value of one line
	* @param array $lineBefor, array with value of line befor
	* @return string
	*/
    function renderRowSimbol($lineNow,$lineBefor)
    {
		$rowResult=""; 
		foreach($lineNow as $key=>$value) //for every element of line check is space or other caracter and print simbols 
		{
			$value=str_replace(PHP_EOL,"",$value);
			if($value!=" ")
			{
				//if need add +
				if( !isset($lineNow[$key-1]) || ( $lineNow[$key-1]==" " && !isset($lineBefor[$key-1]) ) || ( $lineNow[$key-1]==" " && isset($lineBefor[$key-1]) && $lineBefor[$key-1]==" " ) ) 
				{ 
					$rowResult.="+";
				} 
				$rowResult.="---+";
			}
			else
			{
				if(isset($lineBefor[$key]) && $lineBefor[$key]!=" ")
				{
					//if need add +
					if((!isset($lineBefor[$key-1]) or $lineBefor[$key-1]==" ")) 
					{ 
						$rowResult.="+";
					} 
					$rowResult.="---+";
				}
				else
				{
					//if need add " "
					if( !isset($lineNow[$key-1]) || ( $lineNow[$key-1]==" " && !isset($lineBefor[$key-1]) ) || ( $lineNow[$key-1]==" " && isset($lineBefor[$key-1]) && $lineBefor[$key-1]==" " ) )
					{ 
						$rowResult.=" ";
					} 
					$rowResult.="   ";
				}
			} 
		}
		return $rowResult;
	}
	
	
	/**
	* Function render row with value and simbol " | " for terminal table
	* @param array $lineNow, array with value of one line
	* @param array $lineBefor, array with value of line befor
	* @return string
	*/
    function renderRowValue($lineNow,$lineBefor)
	{
		$rowResult=""; 
		foreach($lineNow as $key=>$value) //for every element of row print value and if need aditional caracter
		{
			$value=str_replace(PHP_EOL,"",$value);
			if($value!=" ")
			{
				if(!isset($lineNow[$key-1]) || $lineNow[$key-1]==" ") $rowResult.="|";
				$rowResult.=" ".$value." |";
			}
			else
			{
				if(!isset($lineNow[$key-1]) || $lineNow[$key-1]==" ") $rowResult.=" ";
				$rowResult.="   ";
			}
		}
		return $rowResult;
	}
	
	/**
	* Function read file and check is ip in one of range
	* @param string $file, path to file with range
	* @param string  $ip, ip address
	* @return string
	*/
	function ipLookupFromFile($file,$ip)
	{
		if(file_exists($file)) //check is file path ok
		{ 
			if(preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ip))
			{
				$fileHandle = fopen($file, "r");
				$result=array();;
				while (!feof($fileHandle)) { //read file, line by line
					$line = fgets($fileHandle);
					$line=str_replace(PHP_EOL,"",$line);
					if(preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/\d{1,2}$/', $line))
					{
						list($range,$mask)=explode('/',$line);
						if(checkIsInRange($ip,$range,$mask))
						{	
							$result[]=$line;
						}	
					}
				}
				fclose($fileHandle);
				if(count($result)==0)
					return "No result found!";
				else
					return join(PHP_EOL,$result).PHP_EOL; //join result and print 
			}
			{
				return "Invalide format of ip address";
			}
		}
		else
		{
			return "File '$file' not found";
		}
	}
	/**
	* Function read database and check is ip in one of range
	* @param string $file, path to file with range
	* @param string  $ip, ip address
	* @return string
	*/
	function ipLookupFromDatabase($file,$ip)
	{ 
		if(file_exists($file)) //check is file path ok
		{
			//because need ip range find by sql statement i add some changes in my code
			//now I am compare ip and range in long int format (fist convert to in)
			//beacuse that I add new column in database for save int value of ip range
			//int presentation is the best for save in database if need do compare 
			$result=array();
			$intIp=ip2long($ip);
			$connect= new SQLite3($file);
			$data=$connect->query("SELECT * FROM ip_address WHERE (ip_number & (~((1<<(32-mask))-1))) = (".$intIp." & (~((1<<(32-mask))-1)))"); 
			if($data!=false)
			{
				while ($row = $data->fetchArray()) { 
					$result[]=$row["ip"]."/".$row["mask"];
				} 
				if(count($result)>0) 
					return join(PHP_EOL,$result).PHP_EOL; //join result and print 	
			}
			return "No result found!";
			
		}
		else
		{
			return "File '$file' not found";
		}
	}

	/**
	* Function chechk is ip in range
	* @param string  $ip, ip address
	* @param string  $range, ip range
	* @param int  $mask, ip subnet mask
	* @return string
	*/
	function checkIsInRange($ip,$range,$mask)
	{
		
		//ip comparison will do on int value of ip, and range
		//i will use function ip2long(x.y.c.k), ip2long return long int value of ip
		//ip2long(x.y.c.k) => (x << 24) | (y << 16) | (c << 8) | (k)
		
		$intIp=ip2long($ip);
		$intRange=ip2long($range);
		
		//mask will convert to longo also
		$mask = (1 << (32-$mask)) - 1 ;
		$mask = ~ $mask; // need inverse value
		
		if( ($intIp & $mask) == ($intRange & $mask) ) //binary compare, and first $mask bite must be same
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
?>
