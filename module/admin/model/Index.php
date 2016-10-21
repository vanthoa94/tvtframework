<?php 

class Index extends Database{
	public function getText(){
		return "admin";
	}
	public function SLTuVungChuaCoAudio(){
		return $this->from('tu_vung')->where(array('AUDIO'=>''))->count('MATU');
	}
	public function SLNPChuaCoVD(){
		return $this->from('ngu_phap')->where(array(new TCode('(select count(MANP) from nguphap_vd where MANP=ngu_phap.MANP)=0')))->count('MANP');
	}
	public function Dem(){
		return $this->select("(select count(MATU) from tu_vung) as sltv,(select count(MAHT) from han_tu) as slht,(select count(MANP) from ngu_phap) as slnp,(select count(MANV) from nhan_vien) as slnv")->from('tu_vung')->row();
	}
}