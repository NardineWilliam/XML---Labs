<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

  <xsl:output method="html" encoding="UTF-8"/>

  <xsl:template match="/">
    <html>
      <head>
        <title>Employee Information</title>
        <style>
          body {
            font-family: Arial, sans-serif;
          }
          table {
            width: 100%;
            border: 2px solid purple;
          }
          th, td {
            border: 1px solid purple;
            padding: 10px;
            text-align: left;
          }
          th {
            background-color: #d8b8f9; 
            color: white;
          }
        </style>
      </head>
      <body>
        <h2>Employee Information</h2>
        <table>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Numbers</th>
            <th>Addresses</th>
          </tr>
          <xsl:apply-templates select="employees/employee"/>
        </table>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="employee">
    <tr>
      <td><xsl:value-of select="name"/></td>
      <td><xsl:value-of select="email"/></td>
      <td>
        <xsl:for-each select="phones/phone">
          <xsl:value-of select="concat(., ' - ', @type)"/>
           <br/>
        </xsl:for-each>
      </td>
      <td>
        <xsl:for-each select="addresses/address">
          <xsl:value-of select="concat(buildingNumber, ', ', street , ' - ', city, ', ', country)"/>
          <br/>
        </xsl:for-each>
      </td>
    </tr>
  </xsl:template>

</xsl:stylesheet>

