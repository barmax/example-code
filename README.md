## Introduce CZ
- Třída NotificationManager je funkční ale špatně napsaný a zastaralý kód. 
- Práci s databází zajištuje ORM Elloquent (https://laravel.com/docs/master/eloquent).
- Základem třídy jsou její vlastnosti $client a $countryCode. 
	- $client je instance třídy Client. Třída Client implementuje interface s jedinou metodou:  send($payload)
	- $countryCode je string s kódem země na kterou se rozesílka vztahuje.

- Váš úkol: 
	- Proveďte refactoring této třídy, tak aby splňovala obecné zásady programování.
	- Využijte přednosti posledních verzí PHP.
	- Interface třídy NotificationManager musí zůstat kompatibilní.

## Introduce ENG
- The NotificationManager class is a functional but bad written and legacy code what could be written more effective. 
- Work with the database is provided by ORM Elloquent (https://laravel.com/docs/master/eloquent).
- Base attributes of the class are $client a $countryCode. 
	- $client is instance of the class Client. Class Client implements the interface with only one method:  send($payload)
	- $countryCode is string with iso code of the country what is related to notification.

- Your task: 
	- Do the refactor of this class to conform to the general programming principles.
	- With using latest PHP features.
	- The interface of the NotificationManager class must stay compatible.
	
	
## Refactoring

The refactoring has 2 options. The first option (NotificationManager) is wide refactoring than the second. The first
 first gives more flexible, strict and testable code. The second (NotificationManager2) is speedy but overhead because
  the manager must not validate input data. It's not manager work. 
	
