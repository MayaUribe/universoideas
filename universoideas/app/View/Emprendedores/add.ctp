<input id="page_code" type="hidden" value="emprendedor"/>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-10">
            <h1>Publicar trabajo de emprendimiento</h1>
            <hr>
            
            <?php echo $this->Form->create('Emprendedore', array('class' => 'form-horizontal')); ?>
            
            <div class="form-group required">
                <label for="title" class="col-md-3 control-label pt0">Título</label>
                <div class="col-md-9">
                    <?php 
                    echo $this->Form->input('Emprendedore.title', array('label' => false, 'class' => 'form-control', 'type' => 'text', 'required' => 'required'));
                    ?>
                </div>
            </div>
            
            <div class="form-group required">
                <label for="resume" class="col-md-3 control-label pt0">Resumen</label>
                <div class="col-md-9">
                    <?php 
                    echo $this->Form->input('Emprendedore.resume', array('label' => false, 'class' => 'form-control', 'type' => 'textarea', 'rows' => '4', 'required' => 'required'));
                    ?>
                </div>
            </div>
            
            <div class="form-group required">
                <label for="description" class="col-md-3 control-label pt0">Descripción</label>
                <div class="col-md-9">
                    <?php 
                    echo $this->Form->input('Emprendedore.description', array('label' => false, 'class' => 'form-control', 'type' => 'textarea', 'rows' => '14','required' => 'required', 'class' => 'ckeditor'));
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-offset-3 col-md-9"></div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-3 col-md-9">
                    <button id="btn-signup" type="submit" class="btn btn-primary"><i class="icon-hand-right"></i>Guardar</button>
                    <button type="button" class="btn btn-danger"><i class="icon-hand-right"></i>Cancelar</button>
                </div>
            </div>
            
            <?php echo $this->Form->end(); ?>
        </div>
        <div class="col-md-4 col-sm-2">
            <?php include ("includes/siguenos.htm") ?>
            <div id="publicidadventana5" class="p5 tac"><div class="publicidad tal">ESPACIO PUBLICITARIO</div><a href="#"><img src="/img/publicidad/300x250.gif" width="300" height="250" alt="Publicidad" /></a></div>
        </div>
    </div>
</div>