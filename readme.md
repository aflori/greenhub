# Greenhub

## constraint

greenhub is a french start-up and ask myself to create their internet e-commerce website.
<br>
That site must mainly allow to

- sell ecologic product
- have a list of company selling ecologic service
- have a filter system for finding appropriate product
- see a list of customer-made comments and rating on product
<br>
<br>
also, the website must contain

- information about environmental impact on each product
- a blog to share information on product(s)
- a high quality photo with a small data weight
- a CO2 rapport on the site performance as we can see [here](https://www.websitecarbon.com/)

<br>
Finally, the website must be maintanable for allowing other developer to continue developping it.
<br>
To make that we decided to create a SPA application with its associated API

## server
### server side service

The API might allow to
- have authentification features, with roles (login, register, logout).
- have an admin see any information on its user (if not deleted)
- have an admin that can create an user account with any role
- have an admin that can edit an user account
- have an admin that can delete an user account if he did not do an order, else removing personnal information
- have an administrator create product
- have user see all products (with or without filters)
- have an authentificated user make a command on a list of product
- have an authentificated user make a comment and/or rating
- have an authentificated user update a cmment and/or rating
- have an user to see a blog article
- have an authentificated user comment an article
- have an admin create an article
- have an admin modify an article
- have an admin update an article
- have an admin delete an article
- have an user see all company data
- have an authentificated user comment or review a company
- have an user in the company update the company data
- have an user in the company delete it from the site
- have an admin create a company
- have an admin update a company
- have an admin delete a company
- have an admin to associate a user with a company
- have an authentificated user make an order with a list of order
- have an authentificated user see the history of its order
- have an admin see the history of all the previous made order

