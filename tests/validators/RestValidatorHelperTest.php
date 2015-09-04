<?php

/**
 * Tests for the rest validation helper.
 * @todo: add more tests
 */
class RestValidatorHelperTest extends SapphireTest {

    public function testIsUrl() {
        $correctUrls = [
            'https://example.com',
            'http://example.com',
            'http://foo.com/blah_blah',
            'http://foo.com/blah_blah/',
            'http://foo.com/blah_blah_(wikipedia)',
            'http://foo.com/blah_blah_(wikipedia)_(again)',
            'http://www.example.com/wpstyle/?p=364',
            'https://www.example.com/foo/?bar=baz&inga=42&quux',
            'http://df.ws/123',
            'http://userid:password@example.com:8080',
            'http://userid:password@example.com:8080/',
            'http://userid@example.com',
            'http://userid@example.com/',
            'http://userid@example.com:8080',
            'http://userid@example.com:8080/',
            'http://userid:password@example.com',
            'http://userid:password@example.com/',
            'http://142.42.1.1/',
            'http://142.42.1.1:8080/',
            'http://fg.ws/',
            'http://fg.ws',
            'http://foo.com/blah_(wikipedia)#cite-1',
            'http://foo.com/blah_(wikipedia)_blah#cite-1',
            'http://foo.com/unicode_(✪)_in_parens',
            'http://foo.com/(something)?after=parens',
            'http://☺.damowmow.com/',
            'http://code.google.com/events/#&product=browser',
            'http://j.mp',
            'ftp://foo.bar/baz',
            'http://foo.bar/?q=Test%20URL-encoded%20stuff',
            'http://مثال.إختبار',
            'http://例子.测试',
            'http://उदाहरण.परीक्',
            "http://-.~_!$&'()*+,;=:%40:80%2f::::::@example.com",
            'http://1337.net',
            'http://a.b-c.de',
            'http://223.255.255.254'
        ];

        $incorrectUrls = [
            'http://',
            'http://.',
            'http://..',
            'http://../',
            'http://?',
            'http://??',
            'http://??/',
            'http://#',
            'http://##',
            'http://##/',
            'http://foo.bar?q=Spaces should be encoded',
            '//',
            '//a',
            '///a',
            '///',
            'http:///a',
            'foo.com',
            'rdar://1234',
            'h://test',
            'http:// shouldfail.com',
            ':// should fail',
            'http://foo.bar/foo(bar)baz quux',
            'ftps://foo.bar/',
            'http://-error-.invalid/',
            'http://-a.b.co',
            'http://a.b-.co',
            'http://0.0.0.0',
            'http://10.1.1.0',
            'http://10.1.1.255',
            'http://224.1.1.1',
            'http://1.1.1.1.1',
            'http://123.123.123',
            'http://3628126748',
            'http://.www.foo.bar/',
            'http://.www.foo.bar./',
            'http://10.1.1.1',
            'http://10.1.1.254'
        ];

        foreach($correctUrls as $url) {
            $t = $url;
            $this->assertTrue(RestValidatorHelper::is_url($url), "$t should be valid");
        }

        foreach($incorrectUrls as $url) {
            $t = $url;
            $this->assertFalse(RestValidatorHelper::is_url($url), "$t should be invalid");
        }
    }

    public function testValidateCountryCode() {
        $this->assertEquals('US', RestValidatorHelper::validate_country_code(['cc' => 'US'], 'cc'));
        $this->assertEquals(null, RestValidatorHelper::validate_country_code([], 'cc', false));

        $this->assertException(function() {
            RestValidatorHelper::validate_country_code(['cc' => 'FOO'], 'cc');
        }, 'ValidationException');
        $this->assertException(function() {
            RestValidatorHelper::validate_country_code([], 'cc');
        }, 'ValidationException');
    }

    public function testValidateEmail() {
        $this->assertEquals('j@d.com', RestValidatorHelper::validate_email(['mail' => 'j@d.com'], 'mail'));

        $this->assertException(function() {
            RestValidatorHelper::validate_email(['mail' => 'FOO'], 'mail');
        }, 'ValidationException');

        $this->assertEquals(null, RestValidatorHelper::validate_email([], 'mail', false));
        $this->assertException(function() {
            RestValidatorHelper::validate_email([], 'mail');
        }, 'ValidationException');
    }

    public function testValidateUrl() {
        $this->assertEquals('http://test.com', RestValidatorHelper::validate_url(['url' => 'http://test.com'], 'url'));

        $this->assertException(function() {
            RestValidatorHelper::validate_url(['url' => 'FOO'], 'url');
        }, 'ValidationException');

        $this->assertEquals(null, RestValidatorHelper::validate_url([], 'url', false));
        $this->assertException(function() {
            RestValidatorHelper::validate_url([], 'url');
        }, 'ValidationException');
    }


    /**
     * @param callable $callback
     * @param string $expectedException
     * @param null $expectedCode
     * @param null $expectedMessage
     * @author VladaHejda
     */
    protected function assertException(callable $callback, $expectedException = 'Exception', $expectedCode = null, $expectedMessage = null) {
        if (!ClassInfo::exists($expectedException)) {
            $this->fail(sprintf('An exception of type "%s" does not exist.', $expectedException));
        }
        try {
            $callback();
        } catch (\Exception $e) {
            $class = ClassInfo::class_name($e);
            $message = $e->getMessage();
            $code = $e->getCode();
            $errorMessage = 'Failed asserting the class of exception';
            if ($message && $code) {
                $errorMessage .= sprintf(' (message was %s, code was %d)', $message, $code);
            } elseif ($code) {
                $errorMessage .= sprintf(' (code was %d)', $code);
            }
            $errorMessage .= '.';
            $this->assertInstanceOf($expectedException, $e, $errorMessage);
            if ($expectedCode !== null) {
                $this->assertEquals($expectedCode, $code, sprintf('Failed asserting code of thrown %s.', $class));
            }
            if ($expectedMessage !== null) {
                $this->assertContains($expectedMessage, $message, sprintf('Failed asserting the message of thrown %s.', $class));
            }
            return;
        }
        $errorMessage = 'Failed asserting that exception';
        if (strtolower($expectedException) !== 'exception') {
            $errorMessage .= sprintf(' of type %s', $expectedException);
        }
        $errorMessage .= ' was thrown.';
        $this->fail($errorMessage);
    }

}