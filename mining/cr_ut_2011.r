# Source: Umrtnostni tabulky, CSU
# URL: http://www.czso.cz/csu/redakce.nsf/i/umrtnostni_tabulky_za_cr_od_roku_1920/$File/cr_ut_1920_2011.zip
# Full variable description at http://www.czso.cz/csu/redakce.nsf/i/umrtnostni_tabulky_metodika

library(gdata)

# Configure to your liking
setwd("~/Coding/Ratadata/ratadata-savings/mining")
folder <- "/Users/Simon/Coding/Ratadata/ratadata-savings/mining/cr_ut_1920_2011/" # change path

# Load Excel files
year <- 2011
males <- read.xls(paste(folder, "UT", year, "M", ".XLS", sep=""), skip=2)
females <- read.xls(paste(folder, "UT", year, "Z", ".XLS", sep=""), skip=2)

# Purge empty columns
males <- Filter(function(x)!all(is.na(x)), males)
females <- Filter(function(x)!all(is.na(x)), females)

# Rename columns (accessible with males$age, males$ex, ...)
names(males) <- gsub("věk..", "", names(males))
names(females) <- gsub("věk..", "", names(females))

# Adds variable with expected terminal age
males$total <- males$age + males$ex
females$total <- females$age + females$ex

# If I want to know my expected total age, I will query
# > males$total[males$age == 21]
# or simply
# > males[21 + 1] # R's first row index is 1, datatable begins at 0

# Save to semicolon-separated .csv files
write.table(males, file="ut2011m.csv", sep=";", row.names=F)
write.table(females, file="ut2011z.csv", sep=";", row.names=F)