# Laravel Interview Practical

The website in this repository has many bugs and hasn't been written very well. The parts below are designed to test how you address these problems and add new features. **We are *not* worried about page layout or styling.**

## Instructions

- Please clone this repository, and commit your changes for each part.
- Please either create a Pull-Request with your work, or send over a zipped copy (including the .git directory).

## Part 1
- Improve the routing used in the site.
- Add validation to the new product process and make sure the product's name is unique.

## Part 2
- Fix any security issues you notice in the ProductController.
- Fix any security issues or bugs, and make improvements to the blade template (*don't worry about layout and styling*).

## Part 3
Currently, the "description" field in the form doesn't do anything.
- Please update the products table to include a "description" field, and populate it from this form.

## Part 4
Currently, the "tags" field in the form doesn't do anything. We would like to create tags for new products:
- Create a new Tag model, and a new pivot table to link the Products to the Tags (many-to-many).
- Take the tags string when the form is submitted and split it by commas.
- Create a tag for each save it - but only if it's unique.
- Link the product to each one (whether the tags were new or existed from before).
