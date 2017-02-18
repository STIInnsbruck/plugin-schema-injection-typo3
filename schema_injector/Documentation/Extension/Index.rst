Our Extension and how it works
==============================

General
-------

.. image:: /image/work_flow.png

As you can see in the figure above we built a backend module and a frontend plugin.

The backend module displays the user interface and is used to upload JSON-LD files to the server.
Furthermore it is needed to inject uploaded JSON-LD files into pages and also to delete injections from pages.

The frontend plugin does the injection when a page gets rendered. It contains some functions which get called each time a page gets rendered and then injects the associated file(s).
Some minimal file checks are done, e.g. if the schema annotated code snippet already contains script-tags, it leaves them out in the injected code.


Interface
---------

.. image:: /image/backend_module.png

On the top of the interface is a file uploader. We did not make any specific validation tests for those files.
It just will be checked if it is a JSON file. The responsibility of a correct JSON-LD file relies on the user.

Below the file uploader it is possible to select an already uploaded JSON-LD file. This field is necessary because having two identically named files is not allowed.

Further down, the page input field is placed. The user has to name all the pages, where the uploaded or selected JSON-LD file should be injected.

As a comfort feature the extension allows a **wildcard %** and multiple page selection, deliminated by space.
For example, inserting the page name % would select ALL pages.

.. note::

   Renaming pages has no affect in the behaviour of the plugin, since pages are associated by their ID, not by their name.

   If page-file-associations are deleted in our plugin, also the physical file on the server gets deleted, if there are no usages for this file anymore.
