# Vanilla icon picker

![GitHub package.json version](https://img.shields.io/github/package-json/v/appolodev/icon-picker?color=blue&style=flat-square)
![npm](https://img.shields.io/npm/dm/vanilla-icon-picker?color=%2325b5ba&style=flat-square)
![GitHub](https://img.shields.io/github/license/appolodev/icon-picker?style=flat-square)
[![pages-build-deployment](https://github.com/AppoloDev/vanilla-icon-picker/actions/workflows/pages/pages-build-deployment/badge.svg)](https://github.com/AppoloDev/vanilla-icon-picker/actions/workflows/pages/pages-build-deployment)

### Icons includes:

- FontAwesome 6&7 (Brands, Solid and Regular)
- Material Design Icons
- Iconoir

## Installation

➡️ Using a package manager

```bash
npm i vanilla-icon-picker
```
```js
// One of the following themes
import 'vanilla-icon-picker/dist/themes/default.min.css'; // 'default' theme
import 'vanilla-icon-picker/dist/themes/bootstrap-5.min.css'; // 'bootstrap-5' theme

import IconPicker from 'vanilla-icon-picker';
```

> ⚠️ Attention: If you use bootstrap theme don't forget to include bootstrap 5 css.

➡️ Using script
```html
<script src="https://cdn.jsdelivr.net/npm/vanilla-icon-picker@1.3.1/dist/icon-picker.min.js"></script>
```

and stylesheet
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-icon-picker@1.3.1/dist/themes/default.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-icon-picker@1.3.1/dist/themes/bootstrap-5.min.css">
```

## Usage

```javascript
const iconPicker = new IconPicker('input', {
    // Options
});
```

[Live demo →](https://appolodev.github.io/vanilla-icon-picker/)

## Options

> 💙 You can find icons sets at [Iconify](https://github.com/iconify/icon-sets/tree/master/json)


```javascript
{
    // Change icon picker's theme
    theme: 'default' | 'bootstrap-5',

    // Set icon(s) library(ies)
    // iconSource: [
    //     'FontAwesome Brands 7', 
    //     'FontAwesome Solid 7', 
    //     'FontAwesome Regular 7', 
    //     'Material Design Icons', 
    //     'Iconoir', 
    //     {
    //         key: 'academicons',
    //         prefix: 'ai ai-',
    //         url: 'https://raw.githubusercontent.com/iconify/icon-sets/master/json/academicons.json'
    //     }
    // ]
    iconSource: [],

    // Close icon picker modal when icon is selected
    // If is `false` save button appear
    closeOnSelect: true,
    
    // Set a default value, preselect for example
    // icon's value and icon's name work
    defaultValue: null,
        
    // Translatable text
    i18n: {
        'input:placeholder': 'Search icon…',
            
        'text:title': 'Select icon',
        'text:empty': 'No results found…',
            
        'btn:save': 'Save'
    }
}
```

## Events

Use the `on(event, callback)` and `off(event, callback)` functions to bind / unbind eventlistener.

| Event    | Description                                                                                                                   | Arguments            |
|----------|-------------------------------------------------------------------------------------------------------------------------------|----------------------|
| `select` | Icon is selected, return icon value, name, svg and unicode if exist                                                           | `Object`             |
| `save`   | Fired when saved with button or if `closeOnSelect` option is `true`, return return icon value, name, svg and unicode if exist | `Object`             |
| `loaded` | All icons are loaded                                                                                                          | `void`               |
| `clear`  | `clear()` method is called                                                                                                    | `void`               |
| `show`   | Modal is shown                                                                                                                | `IconPickerInstance` |
| `hide`   | Modal picker is hidden                                                                                                        | `IconPickerInstance` |

```javascript
iconPicker.on('select', instance => {
    console.log('Select:', instance);
});
```

## Methods

After we initialize IconPicker, we have access instance. Let's look list all available methods:

| Method                    | Description                                                                  |
|---------------------------|------------------------------------------------------------------------------|
| `on()`                    | Add event handler                                                            |
| `off()`                   | Remove event handler                                                         |
| `open()`                  | Open IconPicker's modal                                                      |
| `hide()`                  | Remove IconPicker's modal                                                    |
| `clear()`                 | Clear current icon                                                           |
| `isOpen()`                | Check if open or not                                                         |
| `iconsLoaded()`           | Check if the icons are loaded                                                |
| `destroy(deleteInstance)` | Set it to false (by default it is true) to not to delete IconPicker instance |

## Icon format setting in JSON files

While this picker uses icon sets found at [Iconify](https://github.com/iconify/icon-sets/tree/master/json), it supports
an extension to their format to allow improved performance with large icon sets.

By default, those icon sets include the actual SVG directly, and the picker includes the SVG markup inline. In cases
where the actual SVGs are not needed (e.g. if you're using Font Awesome and the required CSS / JavaScript is
included on the page), adding a new, optional `iconFormat` setting to the JSON file will allow you to remove the SVGs
and reduce file sizes by over 90%, making the loading of the picker much faster.

`iconFormat` is optional and can be set to three different values:

- `svg` (the default) - `body` must include the full SVG.

- `i` - `body` is not needed at all. The picker will use markup like `<i class='far fa-abacus'></i>`.

Example JSON (snipped, `iconFormat` can be set to "i", `body` can be empty or missing entirely)
```json
{
  "prefix": "far fa-",
  "iconFormat": "i",
  "info": {
    "name": "Font Awesome Regular"
  },
  "lastModified": 1689174287,
  "icons": {
    "abacus": {
      "body": "",
      "width": 576
    },
    "acorn": {
      "width": 448
    }
  }
}
```

- `markup` - The picker uses the actual markup set in the `body`. This allows the use of different, custom markup for
icons, e.g. `<span class='far fa-abacus'></span>`.

Example JSON (snipped, `iconFormat` must be set to "markup", `body` must be set)
```json
{
  "prefix": "far fa-",
  "iconFormat": "markup",
  "info": {
    "name": "Font Awesome Regular"
  },
  "lastModified": 1689174287,
  "icons": {
    "abacus": {
      "body": "<span class='far fa-abacus'></span>",
      "width": 576
    }
  }
}
```

To take advantage of this, you could download the JSON file and use search-and-replace to remove the body values from
the file. Then, you would need to set `iconSource` per the Options section above to use your new JSON file.

## Licence

MIT Licence
