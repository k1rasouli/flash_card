## Docker
This app works with Laravel Sail and working Docker application is a required.
## Installation
Considering your machine, and it's configuration, docker installation process can be different. Please check docker's [documentation](https://docs.docker.com/) to find out about the solution that suits you.
***
## Laravel Sail
To check if app's dependencies are working correctly; open project's root path in your terminal window and run `./vendor/bin/sail up`. if you saw Laravel's welcome page at http://localhost:8080/, it means everything is good to go.
#### Note: The default web port is set to 8080 in <i style="color: green">.env</i> file. Change <i style="color: green">APP_PORT</i> value if named port is occupied in your machine.
#### NOTE 2: The default mysql port is set to 33006 in <i style="color: green">.env</i> file. Change <i style="color: green">FORWARD_DB_PORT</i> value if named port is occupied in your machine.
#### NOTE 3: Depending on you computer's configuration to access certain files, you may need to use `sudo` at the beginning of every 'sail' command.
## VERY IMPORTANT NOTES:
#### 1. Form this point; running every 'sail' command that will be mentioned bellow, requires `sail up`. So to run other sail commands; please open a new terminal window or tab in your code editor or operating system.
#### 2. If you faced any problems (Like mysql user access error) during runtime please remove local docker cache of sail with `./vendor/bin/sail down --rmi all -v`
***
## Tests
## Running Tests
Use `./vendor/bin/sail artisan test` to check tests passing. You can find written tests in 'app\tests\Features' folder.
#### Note: Running test on MAC arm architecture may require downgrading sail version. Please check Laravel website for more detail.
## Test Remarks
1. This project's test files are mostly focused of testing artisan commands. But you can find some basic DB testing in the files.
2. The entire test logic is based on single user workload. Written tests are not sufficient for multiuser workload.
***
## Migrating and seeding
To check the app with test data please run `./vendor/bin/sail artisan migrate:refresh --seed`. After that you are ready to check functionalities. Of course checking functionalities without seeding the database is available. But seeder will make it easier to check the application much faster.   
***
## Starting the application
1. Before Running app in command window please check <i style="color: green">MULTI_USER</i> constant in <i style="color: green">.env</i> file. Setting this constant to true will activate multiuser functionality. But since multiuser functionality is something that is considered for future, I recommend running the app in single user mode.
2. The next step is to install vendor dependencies. To do that please run `./vendor/bin/sail composer update`
3. To start you should enter `./vendor/bin/sail artisan flashcard:interactive` in command window.
4. Menus and their functionality are self-explanatory.
***
## IMPORTANT CONSIDERATION
This app's security level is to be used on a local machine. If you are planning to used it on production server, please remember to change projects folder (and it's sub folders) permission from 777 to what ever your server's security plan is.
