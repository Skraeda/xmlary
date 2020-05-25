# Xmlary
![CircleCI](https://img.shields.io/circleci/build/github/Skraeda/xmlary)
![Version Badge](https://img.shields.io/packagist/v/skraeda/xmlary)
![License Badge](https://img.shields.io/github/license/Skraeda/xmlary?color=1f59c4)

This package is a set of XML utilities for PHP.

## Examples
Examples of how the writer converts arrays to XML.

Use `toString` to get the XML body string or `toDomDocument` to get a PHP DOMDocument object.

### Basic elements
```php
use Skraeda\Xmlary\XmlWriter;

$writer = new XmlWriter;

$array = [
    'People' => [
        // String value should become <Marie>Unknown</Marie>
        'Marie' => 'Unknown',

        // Null value should become <Lao/>
        'Lao' => null,

        // Boolean value should become <Peter>1</Peter>
        'Peter' => true,

        // Nested value should become <John><Age>20</Age></John>
        'John' => [
            'Age' => 20,
        ]
    ]
];

echo $writer->toString($array);
```

outputs

```xml
<People>
    <Marie>Unknown</Marie>
    <Lao/>
    <Peter>1</Peter>
    <John>
        <Age>20</Age>
    </John>
</People>
```

### Array elements

```php
$array = [
    'Root' => [
        'ArrayOfValues' => [
            // Should create duplicate <Values> elements
            'Values' => [
                'String1',
                2,
                true,
                null
            ]
        ],
        'ArrayOfNestedValues' => [
            // Should create duplicate <Values> elements
            'Values' => [
                [
                    'StringValue' => 'String1'
                ],
                [
                    'BoolValue' => true
                ],
                [
                    'NullValue' => null
                ],
                [
                    'NumericValue' => 123
                ],
                [
                    'Nested' => [1, 2]
                ]
            ]
        ]
    ]
];

echo $writer->toString($array);
```
outputs

```xml
<Root>
    <ArrayOfValues>
        <Values>String1</Values>
        <Values>2</Values>
        <Values>1</Values>
        <Values/>
    </ArrayOfValues>
    <ArrayOfNestedValues>
        <Values>
            <StringValue>String1</StringValue>
        </Values>
        <Values>
            <BoolValue>1</BoolValue>
        </Values>
        <Values>
            <NullValue/>
        </Values>
        <Values>
            <NumericValue>123</NumericValue>
        </Values>
        <Values>
            <Nested>1</Nested>
            <Nested>2</Nested>
        </Values>
    </ArrayOfNestedValues>
</Root>
```

## Writer configuration
This section describes some ways you can customize the `XmlWriter`. You can configure the XmlWriter through interfaces, configure the inner `DOMDocument` object before or after generation or add `keywords` to handle individual elements differently.

### Interfaces
You can set a custom configuration object, validator or value converter via the `XmlWriter` constructor or through setter methods.

#### Example
```php
use Skraeda\Xmlary\Contracts\XmlValueConverterContract;

/**
 * Custom value converter to change boolean values to true / false strings instead of 1 / 0
 */
class CustomXmlValueConverter implements XmlValueConverterContract
{
    public function convert($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        return $value;
    }
}

// Outputs <Root>false</Root>
echo (new XmlWriter)
    ->setConverter(new CustomXmlValueConverter)
    ->toString(['Root' => false]);
```

### Middleware
You can add middleware before or after `DOMDocument` creation.

#### Example
```php
// Add namespaced attribute to root element.
$writer->pushAfterMiddleware(function ($doc) {
    $namespace = $doc->createAttributeNS('http://example.com', 'example:attr');
    $namespace->value = 'foo';
    $doc->firstChild->appendChild($namespace);
});

// Outputs <foo:Root xmlns:example="http://example.com" example:attr="foo">value</foo:Root>
$writer->toString(['foo:Root' => 'value']);
```

### Keywords
Extend the `XmlWriter` with Keywords to add some custom functionality on the element level.

#### Example

```php
use Skraeda\Xmlary\Contracts\XmlKeyword;

class CDataKeyword implements XmlKeyword
{
    public function handle(DOMDocument $doc, DOMNode $parent, $value): void
    {
        $parent->appendChild($doc->createCDATASection($value));
    }
}

echo $writer->extend('cdata', new CDataKeyword)->toString([
    'Root' => [
        'Element' => [
            '@cdata' => 'bar',
        ]
    ]
]);
```

outputs

```xml
<Root><Element><![CDATA[bar]]></Element></Root>
```

### Bootstrap
You can use the `bootstrap` method on the writer to configure it with default keywords.

#### Keywords
* `attributes`: Set element attributes
* `value`: Set element value (Useful if using other keywords in the same node)
* `cdata`: Wraps a value in CDATA block
* `comment`: Wraps a value in comment block

#### Example
```php
$writer = (new XmlWriter)->bootstrap();

$arr = [
    'Root' => [
        '@attributes' => [
            'xmlns:example' => 'http://example.com',
            'example:attr' => 'foo'
        ],
        'Element' => [
            '@attributes' => [
                'foo' => 'bar'
            ],
            '@value' => 'Value'
        ],
        'CData' => [
            '@cdata' => 'Wrapped',
            '@value' => 'NotWrapped'
        ],
        'Comment' => [
            '@value' => 'Value',
            '@comment' => 'Comment'
        ]
    ]
];

echo $writer->toString($arr);
```

### Utilities
#### XmlSerializable
Interface you can define on a model so it can be formatted as XML by an `XmlWriter`.

You need to define `xmlSerialize` on your model which should return an array similar to the above examples.

#### XmlSerialize
Trait you can add on a model to give it a default `xmlSerialize` handler using reflection.

#### XmlMessage
Abstract base class you can extend to give your object a default `xmlSerialize` handler using reflection.

##### Example

```php
use Skraeda\Xmlary\XmlMessage;

class MyMessage extends XmlMessage
{
    public $Public = 'PublicValue';

    protected $Protected = 'ProtectedValue';

    private $Private = 'PrivateValue';

    public $Array;

    public $Inner;

    public function __construct(?MyMessage $Inner = null)
    {
        $this->Array = ['Field' => 'Value'];
        $this->Inner = $Inner;
    }
}

echo $writer->toString(new MyMessage(new MyMessage));
```
outputs
```xml
<MyMessage>
    <Public>PublicValue</Public>
    <Protected>ProtectedValue</Protected>
    <Private>PrivateValue</Private>
    <Array>
        <Field>Value</Field>
    </Array>
    <Inner>
        <MyMessage>
            <Public>PublicValue</Public>
            <Protected>ProtectedValue</Protected>
            <Private>PrivateValue</Private>
            <Array>
                <Field>Value</Field>
            </Array>
            <Inner/>
        </MyMessage>
    </Inner>
</MyMessage>
```


## Development
PHP7.2 CLI dockerfile included, use it to test any new functionality.

### Start
```bash
docker-compose up
```

### Test
```bash
docker-compose exec app vendor/bin/phpunit
```
