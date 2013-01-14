# [MTB Obfuscate Plugin for Wolf CMS](http://www.tbeckett.net/articles/plugins/mtb_obfuscate.xhtml)

## License

This plugin is dually licensed under the MIT and GPL license models.  For more information view the license_overview.txt file in the license folder.

## About MTB Obfuscate

I was worried about placing my email addresses on the web without some sort of obfuscation routine in place.  Billions of SPAM emails every day and I would like to keep my share to an absolute minimum.  With this in mind, I created this Class, originally not as a plugin for Wolf CMS, but just as a Class for my other PHP projects.  A recent change in a project saw me needing this plugin for Wolf CMS and here we are today.

The process in obfuscation is a mix of PHP and JavaScript.  PHP is used to disassemble the email address and modify it into a random string designed to look like a simple http:// weblink.  PHP uses a random string of characters to derive a math sequence that JavaScript can use for the reassembly of a working email address.  For SPAMbots, your email link will look like:

    <a href="https://www.nra-xllrk.vg/hqtdd" rel="nofollow" onclick="javascript:YqeDFLjJI45pWnwSd(this);" onmouseover="javascript:TQxr7i0ZlK6(this);" onmouseout="javascript:TQxr7i0ZlK6(this);" title="Tyler Beckett">Tyler Beckett</a>

I went a bit further, though, and made PHP randomize the JavaScript Variable and Function names.  In the above example, "YqeDFLjJI45pWnwSd" is a function name.  The only time your email address is actually displayed is when you interact with the link.  Hovering your mouse over the link displays your email address, but does not modify the page source.  Clicking on the link activates your Operating System's mail link behavior (perhaps opening Mozilla Thunderbird or Microsoft Outlook) and modifies the page source to show the actual email address until such time as the page is refreshed.  Once refreshed, the JavaScript is re-randomized and the link is encoded again into an http:// weblink.

The encoding routine is based loosely on the ROT13 method.  The characters are rotated a random number of digits based on a sequence of letters representing numbers.  JavaScript then uses the same string of letters to rotate the encoded email back into its original form.

## Install:
To use, please follow these steps:

1. Extract the archive into your /wolf/plugins/ directory. At this point, all you need do is go to your Administration tab in the backend of your Wolf installation and click the checkbox on the far right of the plugin's name.
2. Place the following call at the top of your Layout: <?php $this->Obfuscate = mtb_obfuscate(); ?>  This will initialize the plugin and make the various functions available to you.  If you need to change the varible, you are free to do so, though you'll also need to modify the use variables below.
3. Place the following output call somewhere in the header of your page: <?php $this->Obfuscate->jsOut(); ?>  This will place the randomized JavaScript functions in the header of your page.  If you wish to condense steps 2 and 3 into one, you can do so as long as Step 2 is above Step 3 somewhere between your <head> and </head> tags.

## Use:
To use, simply $this->Obfuscate variable to refer to the plugin's object:

1. __Required:__ $this->Obfuscate->setText('Example Link'); Use this to set the text of link.  This also sets the title.
2. __Required:__ $this->Obfuscate->setEmail('john@doe.net'); Use this to set the desired email address to obfuscate.
3. __Optional:__ $this->Obfuscate->setType('text'); Use this to change between text and image.  The plugin will default to whichever the last usage is or text if this is the first usage.  Valid options are "text" or "image".
4. __Optional:__ $this->Obfuscate->setClass('cssClass'); Use this to set the class element in the link for CSS purposes.  Please note that if you decide to use image type, this will append "Image" to the desired class name.  E.g. "cssClassImage".
5. __Optional:__ $this->Obfuscate->setImageBG('#000000'); Use this to set the image's background colour.  Default is #000000.
6. __Optional:__ $this->Obfuscate->setImageFG('#ffffff'); Use this to set the image's font colour.  Default is #ffffff.
7. __Required:__ $this->Obfuscate->linkOut(); Use this to echo your obfuscated link.

## Example:

    <?php
    	$this->Obfuscate->setType('text');
    	$this->Obfuscate->setText('Tyler Beckett');
    	$this->Obfuscate->setEmail('john@doe.net');
    	$this->Obfuscate->linkOut();
    ?>

Would result in something similar to:

    <a href="http://www.zrs.bxo/frvm" rel="nofollow" onclick="javascript:aunVfvqcfBBcXgMGd4Qdn(this);" onmouseover="javascript:zmbrqKFBHrMvPom(this);" onmouseout="javascript:zmbrqKFBHrMvPom(this);" title="Tyler Beckett">Tyler Beckett</a>

## Please Note:
The image function is still somewhat rudimentary.  It creates a PNG image using a Tahoma.ttf font file.  I have had issues in the past with sizes and causing the text to not be centered or to drift off the sides of the image.  As of 1.0.5 I haven't fixed this.

The text function is what I use on a fairly regular basis.  If you want to display a link with your email, however, you should stick with an image.  If you use like setText('john@doe.net'); you will be defeating the purpose of the plugin and will expose your email to the world.  Perhaps something like setText('Hover over for email'); would be better.

The JavaScript function is designed to be unreadable and to change on every refresh.  If you wish to see the JavaScript in a somewhat readable form, it can be seen in the jsOut() function of the Obfuscate class in obfuscate.php in the plugin's root directory.