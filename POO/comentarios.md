Veo un buen trabajo. Falta completarlo con la persistencia en BD del caso 3.
Aplicas validaciones y restricciones solicitadas y procuras organizarte el código en funciones, lo que facilita la lectura.
Algunos detalles a mejorar:
- Ciertas restricciones es preferible hacerlas lo más dentro del dominio posible. Siempre es mejor que sea el propio Pedido quien te diga si se puede recoger que comprobarlo desde fuera.
- Valores sin definir, usar el NULL. El guión debería usarse sólo al mostrar por pantalla al usuario, pero a la hora de guardar el valor es preferible el NULL. Si no, tendrás problemas al persistir en BD
