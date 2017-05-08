<?php
/*
Task:
В системе авторизации есть ограничение: 
1. логин должен 
	- начинаться с латинской буквы
	- состоять из цифр, латинских букв, точки и подчеркивания 
	- заканчиваться только цифрой
	- минимальная длина логина — один символ, максимальная - 25 символов
Напишите код, проверяющий соответствие входной строки этому правилу.
(*) Реализовать несколько решений поставленной задачи.

Comment:
Если логин всегда начинается с латинской буквы, а заканчивается цифрой - минимальная
длина такого логина будет 2 символа.
*/
namespace Vendor\Package;

class Auth 
{
	//Solution #0 - using regular expression and preg_match
	public static function loginIsValidPreg1($login) 
	{
		return preg_match('/^[A-z][\w\.]{0,23}\d$/', $login, $m);
	}	
	//Solution #1 - using regular expression and preg_match
	public static function loginIsValidPreg2($login) 
	{
		return preg_match('/^[a-zA-Z][a-zA-Z\.\_0-9]{0,23}[0-9]$/', $login, $m);
	}
	//Solution #2 - simple string functions
	public static function loginIsValidStr($login) 
	{
		//check string length
		if (strlen($login)>25 && strlen($login)<2) return false;
		//check the first character
		if ((ord($login[0])<65 || ord($login[0])>90) && 	
			(ord($login[0])<97 || ord($login[0])>122)) {
				return false;
		}
		//check the last character
		if (ord($login[strlen($login)-1])<48 || ord($login[strlen($login)-1])>57) {
				return false;
		}		
		//check the rest of characters
		for ($i=1; $i<strlen($login)-1; $i++) {
			if (
				(ord($login[$i])<65 || ord($login[$i])>90) && 	//A-Z
				(ord($login[$i])<97 || ord($login[$i])>122) &&  //a-z
				(ord($login[$i])<48 || ord($login[$i])>57) && //0-9
				(ord($login[$i])!=46 && ord($login[$i])!=95) //. and _
				) {
					return false;
			}			
		}
		return true;
	}
	//Solution 3 - remove unwanted characters, check length, first and last element
	public static function loginIsValidReplace($login) 
	{
		//check string length
		if (strlen($login)>25 || strlen($login)<2) return false;	
		//remove unwanted characters and compare the length
		$replace = preg_replace("/[^a-zA-Z0-9\_\.]/", '', $login);
		if (strlen($replace)!=strlen($login)) return false;
		//check the first and the last element
		if (in_array($login[0], array('.','_')) || ctype_digit($login[0])) {
			return false;
		}
		if (!ctype_digit($login[strlen($login)-1])) return false;
		return true;
	}
	//Solution 4 - another loop
	public static function loginIsValidLoop($login) 
	{	
		$valid = true;
		//check string length
		if (strlen($login)>25 || strlen($login)<2) $valid = false;			
		$i = 0;
		while ($i<=(strlen($login)-1) && $valid) {
			switch ($i) {
				case 0: 
					$valid = ctype_alpha($login[$i]);
				break;
				case strlen($login)-1:
					$valid = ctype_digit($login[$i]);
				break;
				default:
					$valid = (ctype_alpha($login[$i]) || 
						ctype_digit($login[$i]) || 
						$login[$i] == '.' ||
						$login[$i] == '_'	
					);	
			}
			$i+=1;
		};
		return $valid;
	}
}
$login = "a14b4";
echo "Test 0: ";
echo (Auth::loginIsValidPreg1($login))?"Valid":"Unvalid";
echo "<br>Test 1: ";
echo (Auth::loginIsValidPreg2($login))?"Valid":"Unvalid";
echo "<br>Test 2: ";
echo (Auth::loginIsValidStr($login))?"Valid":"Unvalid";
echo "<br>Test 3: ";
echo (Auth::loginIsValidReplace($login))?"Valid":"Unvalid";
echo "<br>Test 4: ";
echo (Auth::loginIsValidLoop($login))?"Valid":"Unvalid";