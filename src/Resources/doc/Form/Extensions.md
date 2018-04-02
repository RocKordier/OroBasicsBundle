Form Extensions
===============

TextFilterTypeExtension
-----------------------
This extension extended datagrid filters to customize sort order of filter types. Please use const name.

Config sample:
``` yml
datagrids:
    ehdev-basic-grid:
    [...]
        filters:
            columns:
                name:
                    type: string
                    data_name: entity.text
                    options:
                        ehdev_options:
                            filter_sort:
                                - TYPE_ENDS_WITH
                number:
                    type: number
                    data_name: entity.number
                    options:
                        ehdev_options:
                            filter_sort:
                                - TYPE_EQUAL
```
