# lara-assignment
Two different domains and Two Systems Client/Server

Following Routes are available 
 <table>
 <tr> <th>Type</th>
 <th>Url</th>
 <th>Perameter</th>
 <th>Description</th>
 </tr>
 <tr>
 <td> GET
 </td>
 <td> {your_host}/api/shops</td>
 <td> {  } </td>
 <td> List of all create  shop </td>
 </tr>
  <tr>
 <td> POST
 </td>
 <td> {your_host}/api/shop</td>
 <td> { name } </td>
 <td> Create  new shop with name and return shop details </td>
 </tr>
 </tr>
  <tr>
  <td> GET
 </td>
 <td> {your_host}/api/shop/{shop_id}/products</td>
 <td> { } </td>
 <td> List  of all  product in given shop  </td>
 </tr>
  <tr>
 <td> POST
 </td>
 <td> {your_host}/api/shop/{shop_id}/product</td>
 <td> { category, product, discount, price} </td>
 <td> Create  new product in given shop  and return product details </td>
 </tr>
 <td> POST
 </td>
 <td> {your_host}/api/shop/{shop_id}/produc/{product_id}t</td>
 <td> { category, product, discount, price, _method:PUT or PATCH} </td>
 <td> Update  an existing product in given shop  and return updated product details </td>
 </tr>
  <tr>
 <td> DELETE
 </td>
 <td> {your_host}/api/shop/{shop_id}/product/{product_id}</td>
 <td> { } </td>
 <td> Delete   product in given shop  </td>
 </tr>
 
 </table>
