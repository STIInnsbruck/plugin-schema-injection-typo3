Introduction
============

The goal of this project was to develop a TYPO3 extension which allows users to inject schema.org annotated JSON-LD files into their webpages.

Each page can be associated individually with one or more JSON-LD files, which then are
injected dynamically into the page’s **head tag**.

For what it can be used?
------------------------

There are some trends which try to offer web crawlers, like search engines, the possibility to extract data without showing the whole content.
For example, there is a weather services offering weather information on their webpage. The search engine is able to collect weather data which is placed inside the webpage's head tag, namely the specified JSON-LD file. Those weather information then can be listed in the search engine's results list, allowing the user to get information without actually loading the page.

This extension can also be used to inject other JSON files too, because the validity of the given file regarding schema.org markup is not checked.

JSON-LD
-------

JSON-LD is a method of encoding Linked Data. It is built up on the most common data format
used for browser/server communication: **JSON**. As you can see in the example
below, JSON-LD just extends JSON with some key words. We will explain some of these later in
this documentation.


Why JSON-LD?
^^^^^^^^^^^^

* easy readable for both, humans and machines
* standard libraries for nearly any programming language
* manipulating HTML directly is not required, like in other schema mark-up methods (RDFa or microdata)



Example JSON-LD
^^^^^^^^^^^^^^^

Here you can see a minimal example of JSON-LD:

.. code-block:: json
   :linenos:

   <script type=”application/ld+json”>
   {
      ”@context”: ”http://schema.org/”,
      ”@type”: ”Person”,
      ”name”:”Albert Einstein”
   }
   </script>

Those keys with an @ in front are JSON-LD specific keywords.
	**@context** describes the context of this JSON snippet. E.g. web crawlers know they’ve found a JSON-LD snippet with content specified by schema.org.
	
	**@type** tells the application how to interpret the rest of the data. Furthermore it knows what type of data has to be associated with the specific keys.
