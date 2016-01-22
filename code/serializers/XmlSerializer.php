<?php

/**
 * Serializer for xml.
 * @author Christian Blank <c.blank@notthatbad.net>
 */
class XmlSerializer implements IRestSerializer {

    /**
     * The content type.
     * @var string
     */
    private $contentType = "application/xml";

    /**
     * Serializes the given data into a xml string.
     *
     * @param array $data the data that should be serialized
     * @return string a xml formatted string
     */
    public function serialize($data) {
        $xml = new SimpleXMLElement('<result/>');
        $this->toXml($xml, $data);
        return $xml->asXML();
    }

    public function contentType() {
        return $this->contentType;
    }

    private function toXml(SimpleXMLElement $object, array $data) {
        foreach( $data as $key => $value ) {
            if(is_array($value) ) {
                if( is_numeric($key) ){
                    $key = 'item'.$key; //dealing with <0/>..<n/> issues
                }
                $subnode = $object->addChild($key);
                $this->toXml($subnode, $value);
            } else {
                $object->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
}
