import time

init = time.time()

def echotime(msg):
    print msg, time.time() - init, 'seconds'

