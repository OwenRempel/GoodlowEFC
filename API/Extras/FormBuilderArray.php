<?php

$FormBuilderArray = [
    'Routes'=>[
        'aclass'=>[
            'formTitle'=>'Adult Class',
            'formName'=>'AClassAddItem',
            'tableName'=>'AdultClass',
            'fileUpload'=>true,
            'sort'=>'Date',
            'items'=>[
                [
                    'name'=>'Title',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Class Name',
                ],
                [
                    'name'=>'Image',
                    'typeName'=>'FormInput',
                    'type'=>'file',
                    'inputLabel'=>'Class Banner',
                    'accept'=>"png,jpg,jpeg"
                ],
                [
                    'name'=>'Content',
                    'typeName'=>'FormTextarea',
                    'placeolder'=>'Type your content here',
                    'inputLabel'=>'Class Discription'
                ]
               
            ]
        ],
        'blogs'=>[
            'formTitle'=>'Blog',
            'formName'=>'BlogAddItem',
            'tableName'=>'Blogs',
            'fileUpload'=>true,
            'search'=>'Title,Date,Name,Content',
            'sort'=>'Date',
            'UUID'=>true,
            'items'=>[
                [
                    'name'=>'Title',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Blog Title',
                ],
                [
                    'name'=>'Date',
                    'typeName'=>'FormInput',
                    'type'=>'date',
                    'inputLabel'=>'Date',
                ],
                [
                    'name'=>'Name',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Author Name',
                ],
                [
                    'name'=>'Attachment',
                    'typeName'=>'FormInput',
                    'type'=>'file',
                    'inputLabel'=>'Attachement',
                ],
                [
                    'name'=>'Content',
                    'typeName'=>'FormTextarea',
                    'placeolder'=>'Type your content here',
                    'inputLabel'=>'Blog Content'
                ]
            ]
        ],
        'events'=>[
            'formTitle'=>'Event',
            'formName'=>'EventsAddItem',
            'tableName'=>'Events',
            'search'=>'Title,Date,Location,Content',
            'sort'=>'Date',
            'future'=>'Date',
            'items'=>[
                [
                    'name'=>'Title',
                    'UID'=>'EventTitle',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Event Title',
                ],
                [
                    'name'=>'Location',
                    'UID'=>'EventLocation',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Location',
                ],
                [
                    'name'=>'Date',
                    'UID'=>'EventDate',
                    'typeName'=>'FormInput',
                    'type'=>'datetime-local',
                    'inputLabel'=>'Date',
                ],
                [
                    'name'=>'Content',
                    'UID'=>'EventContent',
                    'typeName'=>'FormTextarea',
                    'placeolder'=>'Type your content here',
                    'inputLabel'=>'Content'
                ]
            ]
        ],
        'bulletins'=>[
            'formTitle'=>'Bulletins',
            'formName'=>'BulletinsAddItem',
            'tableName'=>'Bulletins',
            'fileUpload'=>true,
            'sort'=>'Date',
            'items'=>[
                [
                    'name'=>'Url',
                    'typeName'=>'FormInput',
                    'type'=>'file',
                    'inputLabel'=>'File',
                    'accept'=>"png,jpg,jpeg,docx,pdf"
                ],
                [
                    'name'=>'Date',
                    'typeName'=>'FormInput',
                    'type'=>'date',
                    'inputLabel'=>'Date',
                ],
               
            ]
        ],
        'resources'=>[
            'formTitle'=>'Resources',
            'formName'=>'ResourcesAddItem',
            'tableName'=>'Resources',
            'sort'=>'Title',
            'sortDirection'=>'ASC',
            'items'=>[
                [
                    'name'=>'Title',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Resources Title',
                ],
                [
                    'name'=>'Link',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Link',
                ],
                [
                    'name'=>'List',
                    'typeName'=>'FormInput',
                    'action'=>['explode', ','],
                    'type'=>'text',
                    'placeholder'=>'Seperate with commas',
                    'inputLabel'=>'Tags',
                ],
                [
                    'name'=>'Content',
                    'typeName'=>'FormTextarea',
                    'placeolder'=>'Type your content here',
                    'inputLabel'=>'Resource Information'
                ]
            ]
        ],
        'sermons'=>[
            'formTitle'=>'Sermon',
            'formName'=>'SermonsAddItem',
            'tableName'=>'Sermons',
            'fileUpload'=>true,
            'UUID'=>true,
            'search'=>'Title,Tags',
            'sort'=>'Date',
            'items'=>[
                [
                    'name'=>'Title',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'inputLabel'=>'Title',
                ],
                [
                    'name'=>'File',
                    'typeName'=>'FormInput',
                    'type'=>'file',
                    'inputLabel'=>'Presentation File',
                    'accept'=>"png,jpg,jpeg,docx,pdf,ppt,pptx"
                ],
                [
                    'name'=>'Date',
                    'typeName'=>'FormInput',
                    'type'=>'date',
                    'inputLabel'=>'Date',
                ],
                [
                    'name'=>'Audio',
                    'typeName'=>'FormInput',
                    'type'=>'file',
                    'inputLabel'=>'Audio File',
                    'accept'=>"mp3,avi,m4a"
                ],
                [
                    'name'=>'Tags',
                    'typeName'=>'FormInput',
                    'type'=>'text',
                    'placeholder'=>'Seperate with commas',
                    'inputLabel'=>'Tags',
                ]
            ]
        ],
        'youtube'=>[
            'view'=>true
        ],
        'podcast'=>[
            'view'=>true
        ]

    ]
];

