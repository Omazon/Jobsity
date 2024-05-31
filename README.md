
# Jobsity Omar Boza Test

The project was created using DIVI as the main theme and builder to quickly create some slides and the main pages.

Some plugins were used for assistance, such as ACF for custom fields, and PostX to integrate DIVI with Gutenberg blocks.

The main project is a plugin called **Jobsity**, created specifically for the project.

## Front end
The front end was built using Vue.js and Tailwind CSS. I chose Vue.js because of my experience with the library and also because some advanced frontend functionalities were required. We relied on additional libraries for other Vue.js functions. Tailwind CSS was also used to rapidly create components, which is why there is minimal CSS code.

## Backend
In the backend, ACF was used for custom fields and for the options page, in addition to conventional PHP. Custom fields were used in the only two blocks called 'Upcoming Movies' and 'Popular Actors'.

## API
For the API, we utilized https://developer.themoviedb.org/reference/intro/getting-started, and the API keys are located at /wp-admin/admin.php?page=site-settings. Upon restoring the site using the restoration file, the API keys will already be set.

API requests are made from the backend using the hook add_action('rest_api_init'). In the frontend, requests are made to the created APIs. This is done for security reasons, to protect the API Keys.

## Custom blocks
The custom blocks are reused using custom fields to turn them into other variants. For example, the actors block on the homepage requests only 10, but on the actors page, all are required. Therefore, by using a custom field, it's limited to showing only 10.

## Installation
For the installation, we want a fresh WordPress installation, as a first step.

**Second step:** Install the following plugin: [https://wordpress.org/plugins/backup-backup/](https://wordpress.org/plugins/backup-backup/)

**Third step:** Download the file from: [https://drive.google.com/file/d/14rP_2F7Fc0BN1ktCo93PbPrlaRbiuRGq/view?usp=sharing](https://drive.google.com/file/d/14rP_2F7Fc0BN1ktCo93PbPrlaRbiuRGq/view?usp=sharing). Do not use the attached .zip file attached in the email.

**Fourth step:** Use the downloaded file from Google Drive and restore it using the plugin. This file is a backup of the test site. It will automatically change the URLs if necessary.

**Fifth step:** A plugin called Jobsity will appear in the plugin list. You need to activate it. You also need to update the permalinks, **which is important**.

**IF YOU GET A 404 WHILE VIEWING ACTOR OR MOVIE DETAILS, IT'S BECAUSE YOU HAVEN'T UPDATED THE PERMALINKS.**

# Thank you for the opportunity. I took the test with a lot of effort and dedication, and I dedicated all the time to do my best possible.


