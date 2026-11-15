# AI Usage Log

## Milestone 1 – Initial Setup, Static Frontend and Database Planning

### AI Tool Used
ChatGPT

### AI Assistance

During Milestone 1, I used ChatGPT as a support tool for:

- organizing the project folder structure
- getting suggestions for HTML and CSS structure
- improving responsive design
- helping with small JavaScript problems
- explaining some parts of the project requirements
- getting suggestions for the database entities and their relationships

### Important Prompts

Some of the prompts I used included:

- "Help me organize the folder structure for this web application."
- "How can I make this page responsive?"
- "Why is this JavaScript code not working?"
- "Suggest suitable database entities for my project."

### AI Mistakes

#### 1. Incorrect HTML class name

AI suggested a CSS rule using a class name that did not match the class name used in my HTML.

I noticed the problem because the CSS styling was not being applied to the element. I compared the HTML and CSS class names and changed the CSS selector to match the correct class name.

#### 2. Incorrect JavaScript event

AI suggested adding a click event to an element that was not the correct button. When I tested the page, clicking the button did not produce the expected result. I checked the HTML and changed the event listener to the correct button.


## Milestone 2

### Tools used
- Claude (chat): schema design, SQL script,explanations.

### AI mistakes
1. **Wrong advice about Apache.** The AI said Apache was not needed for now, but
   phpMyAdmin runs on Apache, so the page did not open until Apache was started.
2. **Config format.** The AI first proposed config.php as a returned array, then
   switched to a Config class after comparing with the professor's example
   repository (it also allows environment variables for deployment).

### What I did myself
- (e.g. chose column types and ON DELETE rules, ran the SQL in phpMyAdmin,
  tested DAOs, fixed errors, wrote the test accounts.)


## Milestone 3

### Tools used
- Claude (chat): service layer, external API service, OpenAPI documentation.

### AI mistakes
1. **Sample data with fixed dates.** The AI wrote the sample classes with fixed dates (early November).
   A few days later they were in the past, so `getUpcoming` returned an empty list and the
   "class must be in the future" rules could not be tested. I noticed it in the DAO test output.
   The data was rewritten with dates relative to the current date (CURDATE()).
2. **Missing requirement in the Composer instructions.** The AI told me to run `composer require`
   but did not mention that PHP needs the zip extension. The command failed ("zip extension and
   unzip/7z are both missing"); I enabled `extension=zip` in php.ini and it worked.

### What I did myself
- Registered at API Ninjas, got the key and tested the external API, including a wrong key and no network.
- Tested every route with docs/api.http and fixed my own mistakes (a route file that was not
  created, routes required after Flight::start(), starting the server from the wrong folder).
- Checked the business rules by trying to break them (full class, duplicate booking, deleting a bought plan).

