Form Extensions
===============

DatagridFilterTypeExtension
---------------------------
This extension enhances datagrid filters by allowing customization of the sort order of filter options and the removal of specific filter types.

Constants for configuration can be found in `Oro\Bundle\FilterBundle\Form\Type\Filter\`.

Supported filter types:
* Oro\Bundle\FilterBundle\Form\Type\Filter\DateRangeFilterType
* Oro\Bundle\FilterBundle\Form\Type\Filter\NumberFilterType
* Oro\Bundle\FilterBundle\Form\Type\Filter\NumberRangeFilterType
* Oro\Bundle\FilterBundle\Form\Type\Filter\TextFilterType

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
                            filter_remove:
                                - TYPE_LESS_THAN
                            filter_sort:
                                - TYPE_ENDS_WITH
                number:
                    type: number
                    data_name: entity.number
                    options:
                        ehdev_options:
                            filter_remove:
                                - filter_empty_option
                                - filter_not_empty_option
                                - TYPE_GREATER_THAN
                                - TYPE_LESS_THAN
                                - TYPE_IN
                                - TYPE_NOT_IN
                            filter_sort:
                                - TYPE_EQUAL
```
