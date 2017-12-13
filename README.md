# pg-flashcards
RACHEL module to facilitate flashcard based learning.  Slideshow gallery here... http://www.piensagrande.org/blog-single.php?blog=5#Gallery

## Motivation
The goals of this RACHEL module are:
1) to create a useful, code based RACHEL module using only filesystem constructs (no databases). 
2) to facilitate student learning using HTML 5 to combine images, text and sounds.
3) to provide future volunteer opportunities for non-technical volunteers.
4) to implement a method for self directed repetition especially of language resources
5) to implement a method for testing and gamification within RACHEL in the class room.
6) to have available testing resources as examples for Local Content lesson plans.

## Usage

If RACHEL core has been updated to support inclusion of per module files, 
this module supports inclusion of rachel-index.php and rachel-admin.php with translations currently in English and Spanish.

A simple flashcard collection would consist of named images in a directory with png,jpg,gif or svg extensions.  For each named image, an optional audio file may be included with the same name but extension of mp3, wav, and ogg though the browser may not support each.  

Collections may be grouped as subdirectories within a parent and support exists for selecting a collection group for practice and testing.

After selecting a collection, a whiteboard appears with an owl teacher who randomly presents each image file and then displays the image name and plays the image sound file.  A test program is available that will create a multiple choice test format using the names of the images in the collection as choices.

More advanced card formats are also available which support written questions, inlined svg and base64 images, and named answer choices.  See examples covering English vocabulary, Math, and ASL in flashcards directory.

rachel-index.php looks in the flashcards subdirectory and presents links to each top level collection directory.

rachel-admin.php provides a link to cardUpload which allows creation and upload of collections.

## Developer notes 

The majority of this code was last updated in the summer of 2016 which means not all of our recommended best practices for code based modules have not been fully implemented.

On our images, cardUpload.php includes a check for admin authority, but that has been stripped out for this repository pending discussions with RACHEL core on best practices for admin security within modules.

## Useful practices

We are gratefully using github.com/dabeng/OrgChart Copyright (c) 2016 dabeng to provide navigation of tree, though this may be temporary as it may not visually scale once a large number of collections exist.  

Images have either been created or included based on permissive licensing from public repositories such as pixabay.  Please notify us if there is any question of copyrighted material.

## Future direction / TODO

The main next step would be to create a hosted location where volunteers could create card collections.

The UI for adding new card collections needs to be made a bit prettier once a few decisions have been made about how and where collections will be created, stored, selected for download, and managed.  These are utilities assuming an admin user with understanding of flashcard formats currently.

A web-developer volunteer with some time and CSS experience could significantly improve the handling of variable font sizes when a card has too much text.

All TODO items and notes to developers within the code need to be moved out and added as issues in the repository.

It would probably be good to combine the test format within flashcards with the testing format used in Content Shortcuts and for both to be more readily available on Local Content modules.
