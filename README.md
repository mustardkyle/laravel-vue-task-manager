#Run:
composer install

Copy the environment file:
copy the .env.example to your .env

Open the .env file and set your database and domain settings:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password


#Generate application key:
php artisan key:generate
php artisan config:cache


#Run database migrations:
php artisan migrate

#Start the Laravel server:
php artisan serve

#Install frontend dependencies:
npm install

#Start the frontend server:
npm run dev

#Run testing
Copy the tms database to new database (name: tms_testing) - Also check the database credentials in the .env.testing file
php artisan test

#Your application will be running at:
Frontend: http://127.0.0.1:8000


Import the TMS.postman_collection in your POSTMAN application (for reference)
{{base_url}} = http://127.0.0.1:8000/api/backend/
{{auth}} = get from login endpoint

#Admin page
- To access the admin page, in users table in database, set the is_admin column to 1

#Scheduled task
- The auto delete task (>=30days tasks) is set to 12:00AM.
- You can run php artisan schedule:run (will run on 12AM) or you can run php artisan tasks:cleanup (active)

#NOTE
- You can drag and drop the LIST order by holding the list icon 

FRONTEND:
- /bootstrap 
- /public
- /resources

BACKEND:
- /app
- /config
- /database

POSTMAN COLLECTION:
- Found in TMS.postman_collection.json
