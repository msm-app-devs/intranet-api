var age = Number(f("QC")[1].get());

var ranges = [[0,17],[18,24],[25,34],[35,44],[45,54],[55,64],[65,99]];

for(var k = 0; k<ranges.length; k++) {
    Response.Write(ranges[k].length);
    if (InRange(age,ranges[k][0],ranges[k][1])) {
        f("AgeRecode").set(k+1)
    }
}