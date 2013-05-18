/**
 * Function for create table
 */
function createTable()
{
	var table = document.createElement('table');
	
	var numberRows=Math.floor((Math.random()*15)+5);
	for (var i=0;i<numberRows;i++)
	{ 
		var firstName=generateString(Math.floor((Math.random()*10)+3));
		var lastName=generateString(Math.floor((Math.random()*10)+3));
		var email=generateMail(Math.floor((Math.random()*10)+3));
		
		var newRow=table.insertRow(i);
		newRow.onmouseover = rowMouseOver;
        newRow.onmouseout = rowMouseOut;
	    if(i % 2 != 0) newRow.className='odd';
		
		var rId=newRow.insertCell(0).innerHTML=i;
		var rName=newRow.insertCell(1).innerHTML=firstUppercase(firstName) + ' ' + firstUppercase(lastName);
		
		var aEmail=document.createElement('a');
		aEmail.setAttribute("href","mailto:"+email);
		aEmail.innerHTML=email;
		var rMail=newRow.insertCell(2).appendChild(aEmail);
		
	}
	
		var header=table.createTHead();
    var row=header.insertRow(0);
	var hId=row.insertCell(0);
	var hName=row.insertCell(1);
	var hMail=row.insertCell(2);
	hId.innerHTML="ID";
	hName.innerHTML="Name";
	hMail.innerHTML="E-mail";
	hId.className='tc-id';
	hName.className='tc-name';
	hMail.className='tc-mail';

	document.getElementById("content").appendChild(table);
	
	 
}

/**
 * Function change first string caracter to upepercase
 */
function firstUppercase(string)
{
	return string.slice(1,2).toUpperCase()+string.slice(2);
}

/**
 * Function create random string
 */
function generateString(length) {
    var chars='abcdefghijklmnopqrstuvwxyz';
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
    
    return result;
}

/**
 * Function create random email
 */
function generateMail(length) { 
    var result = generateString(length)+'&gmail.com';
    return result;
}
/** 
 * Function add class hovered on mouseover row
 */ 
function rowMouseOver()
{
  this.className=this.className+' hovered';
}

/**
 * Function remove class hovered from mouseout row
 */
function rowMouseOut()
{
  var c=this.className;
  this.className = c.replace(/hovered/,'');
}
