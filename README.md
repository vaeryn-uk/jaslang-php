# Jaslang

A language capable of parsing and evaluating simple expressions. Written in PHP.

The output of a Jaslang expression is a single string. Jaslang supports expressions of arbitrary complexity.
Currently supported language features:

* Functions, including support for easily creating user-defined functions.
* Binary operators, including support for easily creating user-defined operators.
* Simple type system: Number, String and Boolean.
* A parse engine built with distinct lexical and AST-phases.
* Debugging information, including syntax checks and evaluation traces.

## Examples

```
sum(1, 3)                               // "4"
subtract(-4.5, -3)                      // "-1.5"
1 + 3 + 2 + 5 - 2                       // "9"
"ello" === substring("hello", 1, 4)     // "true"
sum(1, sum(2, sum(3, 4))                // "Jaslang syntax error! Input: sum(1, sum(2, sum(3, 4))
                                        //  Unexpected end of input"
foo bar                                 // "Jaslang syntax error! Input: foo bar 
                                        //  Unexpected token: bar @5"
random("hello world")                   // "Jaslang runtime exception! Invalid argument at position 0. Expected "number", got hello world"
```

The list of core functions is currently very limited due to focus on the engire.

## TODO

* CallableRepository registrations passed to Lexer to allow configuration of what operators/functions exist, thus not tied to +-/*^<> operator characters.
* Parentheses to control evaluation precedence.
* Implement some decent core functions.
* Unary operators (bool negation)
* Ternary operator?
* Clean up phar/build
* Return types
* Default operator precedence.
* Work on string-like type.
* Doc-generation tool.
* Multiple expressions? (Chained?)
* AST dumps, allowing Jaslang (or other) interpreters to be written in other languages. PHP does the "compile" phase
* More generic solution to lexer/parser, allowing creation of arbitrary languages
