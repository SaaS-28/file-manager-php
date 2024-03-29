# File manager
Simple **file manager** made with **HTML**, **CSS**, **JS** and **PHP** languages.

The program handles **sending** and **receiving data** with PHP and JS. With **JS** the program **handles** the **AJAX requests** used for keeping the "database" always up to date.
**PHP** instead is **used** for all the **other things**.

**HTML** and **CSS** are **used** to give the user the **best visual experience** possible.

## Installation and use
The **files** are easilly installable and are **arranged correctly** within the *root folder*. You can change the code according to your needs very easily as most of the code is *commented*.

If you want to test the code and then its operation, you can do it by using [XAMPP]("https://www.apachefriends.org/it/index.html"). You can also test it on a real server. That’s why I made a guide for [setupping a local web server with debian]("https://github.com/SaaS-28/local-web-server-setup").

## Button-Version
In this version you search for people by typing in the input field and then clicking the **submit button**. With the button clicked, the AJAX request is sent and processed into a **separate file**. This will bring the visualization of the part of the table concerned.

## Real-Time-Update-Version
In this version the whole process is done in **postback** and then in a single file. The AJAX request is sent simply by searching in the input field, in fact every time the **field is updated**, the request will show different parts of the table according to the one searched.
