# ppupload

Short, basic file upload in PHP, with timed uploads. Made specifically because I wanted my own little file uploader for ShareX.

## setup

1. Clone or extract the contents of this repository to your target directory.
2. Setup your web server (I used Nginx) to point towards the directory.
3. Allow access to `index.php` on your LAN subnet, but block it elsewhere (see `nginx.conf` for a simple example).
4. You're good to go. Give it a try.
5. (Optional) If you would like timed uploads you'll need an installation of Python 2.7.x. All you need to do is setup a cronjob to run every few minutes, hours, whatever. Use the command line arguments to customize just how long you would like files to be available. The default is two hours flat.

## note

I'm not great at PHP, and I wouldn't trust this as a public service. That's exactly why I've restricted uploading only to my LAN subnet. If you see some other major pitfalls, please, let me know or submit a pull request.
