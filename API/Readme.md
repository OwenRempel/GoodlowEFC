# Goodlow API Documentation

- [Routing](https://github.com/OwenRempel/GoodlowEFC/tree/main/API#routing)
- [Authentication](https://github.com/OwenRempel/GoodlowEFC/tree/main/API#authentication)

Route Types
- [GET](https://github.com/OwenRempel/GoodlowEFC/tree/main/API#get)
- [POST](https://github.com/OwenRempel/GoodlowEFC/tree/main/API#post)
- [PUT](https://github.com/OwenRempel/GoodlowEFC/tree/main/API#put)
- [DELETE](https://github.com/OwenRempel/GoodlowEFC/tree/main/API#delete)
  
Special Routing
- [Youtube/Podcast](https://github.com/OwenRempel/GoodlowEFC/tree/main/API#youtubepodcast)
## Routing
Allowed API Data Routes
```
/API/sermons
/API/aclass
/API/blogs
/API/events
/API/bulletins
/API/resources
/API/youtube
/API/podcast
```

Routes For Authentication

```
/API/logout
/API/login
/API/token
```

### Authentication

This web app uses token based auth. As such you will have to make a login call to this route.

```http
POST /API/login
```
The body of your request should include two parameters.

```JSON
{
    "username":"Your Username",
    "password":"Your Password"
}
```
If your data is correct you should see a response like this.
```JSON
{
    "success": "User Logged In",
    "Token": "5697b5839c2656f190b0a0b53883cf4c87794cb7"
}
```
Or if you are already in the Database and your token has not expired, your token will be returned to you as well.
```JSON
{
    "info": "Your already logged in",
    "Token": "5697b5839c2656f190b0a0b53883cf4c87794cb7"
}
```
This token will need to be passed along to any requests you make that are not <code>GET</code> Requests

Lastly when you need to check to see if a token is valid. 
```http
POST /API/token
```
```JSON
{
    "Token":"5697b5839c2656f190b0a0b53883cf4c87794cb7"
}
```
You will Receive data back in this format if it is valid. Error messages should be self explanatory.
```JSON
{
    "success": "Valid Token",
    "Token": "5697b5839c2656f190b0a0b53883cf4c87794cb7"
}
```
## Youtube/Podcast
For the Youtube videos and Podcast videos since we are not adding any data ourselves but requesting the data be updated, it works a bit different.

There are two routes for the videos <code>youtube</code> and <code>podcast</code>.


```http
GET /API/{route}
```
This will make the API call to the service and update the videos that are stored on the Database.
You will receive a success message letting you know how many new videos were added.

To get a list of videos.
```http
GET /API/{route}/list
```

## GET

Request all items
```http
GET /API/{route}
```
To get the layout of the route simply request.
```http
GET /API/{route}/info
```
```JSON
{
    "form": {
        "formName": "FormAddItem",
        "formTitle": "Add Item",
        "callBack": "/API/sermons",
        "fields": [
            {
                "name": "Title",
                "typeName": "FormInput",
                "type": "text",
                "inputLabel": "Title"
            },
            {
                "name": "File",
                "typeName": "FormInput",
                "type": "file",
                "inputLabel": "File"
            },
            {
                "defaultValue": "2022-02-08",
                "name": "Date",
                "typeName": "FormInput",
                "type": "date",
                "inputLabel": "Date"
            }
        ]
    }
}
```

Get one item by ID.

```http
GET /API/{route}/{ID}
```
Or just the latest one.

```http
GET /API/{route}/latest
```

Limiting the amount of items returned through the <code>?limit=number</code> parameter is also available.


```http
GET /API/{route}?limit=10
```

When working with large blocks of text the API automatically shortens the content of those items. This can be prevented through the <code>?full=1</code> parameter

```http
GET /API/{route}?full=1
```


## POST

When Posting to any of the routes it is important to pass the <code>formName</code> as on of the parameters. If you do not do this it will error. This acts as a check to make sure that none of the data goes to the wrong table.

Basic request
```http
POST /API/{route}
```
The body can be a <code>formData</code> object or <code>JSON</code> data.

You will need to include a <code>Token</code> parameter with the body of your request.

To get the parameters you need to pass back you can always request.
```http
GET /API/{request}/info
```




## PUT

For updates you will need to make a <code>GET</code> request to get both data and structure.
```http
GET /API/{route}/info/{ID}?token={token}
```

```JSON
{
    "form": {
        "formName": "formAddItem",
        "formTitle": "Update Form",
        "callBack": "/API/{route}/{ID}",
        "fields": [
            {
                "name": "Title",
                "typeName": "FormInput",
                "type": "text",
                "inputLabel": "Title",
                "defaultValue": "Some Default Value"
            },
            {
                "name": "Name",
                "typeName": "FormInput",
                "type": "text",
                "inputLabel": "Name",
                "defaultValue": "Some Default Value"
            }
        ]
    }
}
```


Some of the fields will not be returned in when making a <code>PUT</code> Request for example you cannot update any files instead you would have to <code>DELETE</code> the item and create a new on. This may be fixed in a later release.

Once you have the updated data you can make a <code>PUT</code> request to:
```http
PUT /API/{route}/{ID}
```
Once again the body can be a <code>formData</code> object or <code>JSON</code> data.
And you need to include a <code>Token</code> parameter as well as a <code>formName</code> parameter.

## DELETE
Basic Request.
```http
DELETE /API/{route}/{ID}?token={token}
```
Since <code>DELETE</code> requests have no body you will have to pass the token in as a <code>GET</code> parameter.