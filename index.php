<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
var c;
var ctx;
var population_size = 50;
var population;
var img_width;
var img_height;
var target;
var mutation_rate = 0.001;
var pop_fitness =  new Array();
var best_fitness = 10000;
var best_idx = 0;

window.addEventListener('load', function () {
              
              function init(){
                  c=document.getElementById("myCanvas");
                  ctx=c.getContext("2d");
                  
                  var img = new Image();
                  img.src = 'image.jpg';
                  img.onload = function(){
                    ctx.drawImage(img, 1, 1);
                    img_width = img.width;
                    img_height = img.height;
                    target = ctx.getImageData(1,1,img_width,img_height);
                     var size = ctx.getImageData(1,1,img.width,img.height).data.length;
                     create_pop(size);
                  }
                 
              }
              function random_individual(gene_size){
                  var x = new Uint8ClampedArray(gene_size);
                  
                  for(var i = 0; i< x.length; i++){
                      x[i] = Math.round(Math.random()*255);
                  }
                  
                  return x;
              }
              function create_pop(gene_size){
                  population = new Array();
                  for(var i =0; i < population_size; i++){
                      population[i] = random_individual(gene_size);
                      
                  }
              }
              
               init();
            }, false);   
function fitness(x){
    var score = 0;
    for(var i = 0; i < target.data.length; i++){
        score += Math.abs(target.data[i] - x[i]);
    }
    return score;
}
function mutation(){
    var gene_size = population[0].length;
    var total_gene = population_size *gene_size;
    var num_mutation = Math.ceil(total_gene * mutation_rate);
    for(var i = 0; i < num_mutation; i++){
       var x = Math.round(Math.random()*(population_size-1));
       var y = Math.round(Math.random()*(gene_size-1));
       if(x==0){x =1;}
       population[x][y] = Math.round(Math.random()*255);
    }
    
}
function cross_over(x1, x2, point){
    var output = new Uint8ClampedArray(x1.length);
    for(var i = 0; i < point; i++){
        output[i] = x1[i];
    }
    for(var i = point; i < x2.length; i++){
        output[i] = x2[i];
    }
    return output;
}
function determine_pop_fitness(){
    for(var i = 0; i < population_size; i++){
        pop_fitness[i] = fitness(population[i]);
        if(i == 0){
            best_idx = 0;
            best_fitness = pop_fitness[i];
        }
        if(pop_fitness[i] < best_fitness){
            best_idx = i;
            best_fitness = pop_fitness[i]; 
        }
    }
}

function find_parent(){
    rand1 = Math.round(Math.random()*(population.length-1));
    rand2 = Math.round(Math.random()*(population.length-1));
    if(pop_fitness[rand1] < pop_fitness[rand2]){
        return rand1;
    }
    return rand2;
}

function perform_crossover(){
    var temp_pop = new Array();
    temp_pop[0] = population[best_idx];
    for(var i =1; i < population.length; i++ ){
        parent1 = find_parent();
        parent2 =  find_parent();
        temp_pop[i] = cross_over(population[parent1],population[parent2],Math.round(Math.random()*population[parent1].length));
    }
    population = temp_pop;
}

function start(){
    determine_pop_fitness();
    var generation = 0;
    while((best_fitness>10)){
        perform_crossover();
        mutation();
        determine_pop_fitness();
        generation++;
       
        if(generation %10 == 0){    
          alert("Evolution has begun...");
          show();           
        }
        
    }
    
}

function show()
{    
    var imgData=ctx.getImageData(1,1,img_width,img_height);
    for(var j = 0; j< population_size; j++){
      for(var i = 0; i < population[j].length; i++ ){
        imgData.data[i] = population[j][i];        
      }    
      ctx.putImageData(imgData,(j%10)*(img_width+10)+1,Math.floor(j/10)*(img_height+10)+70);
     }
     
}
</script>
</head>
    
<body>

<canvas id="myCanvas" width="900" height="450" style="border:1px solid #d3d3d3;">
Your browser does not support the HTML5 canvas tag.</canvas>

<button onclick="start()">Start</button>

</body>
</html>
