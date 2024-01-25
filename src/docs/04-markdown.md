---
title: "Supported Markdown"
---

## Headings

```markdown
# H1
## H2
### H3
#### H4
##### H5
###### H6
```

## Formatting

**bold text**

*italicized text*

~~Strikethrough~~

```markdown
**bold text**

*italicized text*

~~Strikethrough~~
```

## Blockquote

> Blockquote

```markdown
> Blockquote
```

## Lists

1. First item
2. Second item
2. Number used doesn't matter
2. It auto increments the number

- Unordered list
- Item
- Item

* You can use dashes
* or asterisks
* for unordered lists

```markdown
1. First item
2. Second item
2. Number used doesn't matter
2. It auto increments the number

- Unordered list
- Item
- Item

* You can use dashes
* or asterisks
* for unordered lists
```

## Code (or unformatted text)

Code in a `Single line` sentence.

```javascript
function test() {
    return 'Fenced code block'
}
```

```markdown
Code in a `Single line` sentence.
```

```markdown
```javascript   [optional highlighting support using highlight.js]
function test() {
    return 'Fenced code block'
}
\```   [without slash]
``` 

## Horizontal Rule

---

```markdown
---
```

## Links

[Our Website](https://barkleyrei.com)

```markdown
[Our Website](https://barkleyrei.com)
```

## Images

![Have a coffee](/img/_docs/coffee.png)

```markdown
![Have a coffee](/img/_docs/coffee.png)
```

## Tables

| Syntax | Description |
| ----------- | ----------- |
| Header | Title |
| Paragraph | Text |

```markdown
| Syntax | Description |
| ----------- | ----------- |
| Header | Title |
| Paragraph | Text |
```

## Task List

- [x] Write the press release
- [ ] Update the website
- [ ] Contact the media

```markdown
- [x] Write the press release
- [ ] Update the website
- [ ] Contact the media
```

## Markdown Cheat Sheet

Not all syntax on this sheet may be supported in Fractal.

[Cheat Sheet](https://www.markdownguide.org/cheat-sheet/)
